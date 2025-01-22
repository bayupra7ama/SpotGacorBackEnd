<?php

namespace App\Http\Controllers\API;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LokasiController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('nama_tempat');
        $jenis_ikan = $request->input('jenis_ikan');
        $rating_from = $request->input('rating_from');
        $rating_to = $request->input('rating_to');
    
        if ($id) {
            $lokasi = Lokasi::with(['ulasan'])->find($id);
    
            if ($lokasi) {
                // Hitung rata-rata rating
                $averageRating = $lokasi->ulasan->pluck('rating')->average();
                $lokasi->average_rating = $averageRating;
    
                // Tambahkan URL gambar publik
                $lokasi->image_paths = $this->getImagePaths($lokasi->image_paths);
    
                return ResponseFormatter::success(
                    $lokasi,
                    "Data lokasi berhasil diambil"
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data lokasi tidak ada',
                    404
                );
            }
        }
    
        // Mulai query untuk mengambil daftar lokasi
        $lokasiQuery = Lokasi::query();
    
        if ($name) {
            $lokasiQuery->where('nama_tempat', 'like', '%' . $name . '%');
        }
        if ($jenis_ikan) {
            $lokasiQuery->where('jenis_ikan', 'like', '%' . $jenis_ikan . '%');
        }
    
        if ($rating_from) {
            $lokasiQuery->whereHas('ulasan', function ($query) use ($rating_from) {
                $query->where('rating', '>=', $rating_from);
            });
        }
    
        if ($rating_to) {
            $lokasiQuery->whereHas('ulasan', function ($query) use ($rating_to) {
                $query->where('rating', '<=', $rating_to);
            });
        }
    
        // Tambahkan pengurutan berdasarkan created_at agar data terbaru berada di atas
        $lokasiList = $lokasiQuery->with('ulasan')->orderBy('created_at', 'desc')->paginate(20);
    
        // Tambahkan rata-rata rating dan URL gambar untuk setiap lokasi
        $lokasiList->getCollection()->transform(function ($lokasi) {
            $ratings = $lokasi->ulasan->pluck('rating');
            $lokasi->average_rating = $ratings->isNotEmpty() ? $ratings->average() : null;
    
            // Tambahkan URL gambar publik
            $lokasi->image_paths = $this->getImagePaths($lokasi->image_paths);
    
            unset($lokasi->ulasan); // Opsional: Hapus ulasan jika tidak diperlukan di response
            return $lokasi;
        });
    
        return ResponseFormatter::success(
            $lokasiList,
            'Data list berhasil diambil'
        );
    }
    



/**
 * Helper function untuk mengonversi path gambar menjadi URL publik
 *
 * @param string|null $imagePaths
 * @return array
 */
private function getImagePaths(?string $imagePaths): array
{
    if (!$imagePaths) {
        return [];
    }

    $paths = json_decode($imagePaths, true) ?: [];
    return array_map(function ($path) {
        // Menghapus '/storage' yang sudah ada dalam path
        $path = ltrim($path, '/'); // Menghapus karakter '/' di awal jika ada
        $url = Storage::url($path);

        // Pastikan hanya ada satu '/storage' di URL
        return preg_replace('/^\/storage/', '', $url, 1); 
    }, $paths);
}




    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'perlengkapan' => 'required|string|max:255',
            'rute' => 'required|string|max:255',
            'umpan' => 'required|string|max:255',
            'jenis_ikan' => 'nullable|string|max:255',
            'images' => 'required|array',  // Memastikan 'images' adalah array
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        // Ambil nama pengguna yang terautentikasi
        $userName = $request->user()->name;

        // Simpan lokasi baru ke database
        $lokasi = Lokasi::create([
            'user_id' => $request->user()->id,
            'nama_tempat' => $request->input('nama_tempat'),
            'alamat' => $request->input('alamat'),
            'lat' => $request->input('lat'),
            'long' => $request->input('long'),
            'jenis_ikan' => $request->input('jenis_ikan'),
            'created_by' => $userName,
            'perlengkapan' => $request->input('perlengkapan'),
            'rute' => $request->input('rute'),
            'umpan' => $request->input('umpan'),
        ]);

        // Menyimpan gambar dan path-nya ke dalam kolom image_paths
        $uploadedImages = [];
        foreach ($request->file('images') as $image) {
            // Menyimpan gambar di folder public/images dan mendapatkan path-nya
            $path = $image->store('lokasi_photos', 'public');

            // Mengubah path ke URL yang dapat diakses
            $url = Storage::url($path);
            $uploadedImages[] = $url;  // Menambahkan URL gambar ke array
        }

        // Update lokasi dengan path gambar yang baru di-upload
        $lokasi->update([
            'image_paths' => $uploadedImages,  // Simpan URL gambar dalam format JSON
        ]);

        // Mengembalikan response dengan lokasi yang baru disimpan
        return response()->json([
            'message' => 'Lokasi berhasil disimpan!',
            'data' => $lokasi,
        ], 201);
    }






    public function show($id)
    {
        // Temukan lokasi berdasarkan ID dengan ulasan
        $lokasi = Lokasi::with('ulasan.user')->find($id);

        if (!$lokasi) {
            return ResponseFormatter::error(null, 'Lokasi tidak ditemukan', 404);
        }

        // Mengambil rating dari ulasan
        $ratings = $lokasi->ulasan->pluck('rating');
        $averageRating = $ratings->isNotEmpty() ? $ratings->average() : null;

        // Format data yang akan dikembalikan
        $response = [
            'lokasi' => [
                'id' => $lokasi->id,
                'nama_tempat' => $lokasi->nama_tempat,
                'alamat' => $lokasi->alamat,
                'created_by' => $lokasi->created_by,
                'lat' => $lokasi->lat,
                'long' => $lokasi->long,
                'photo' => $lokasi->image_paths,
                'rute' => $lokasi->rute,
                'perlengkapan' => $lokasi->perlengkapan,
                'umpan' => $lokasi->umpan,
                'jenis_ikan' => $lokasi->jenis_ikan,
            ],
            'average_rating' => $averageRating,
            'ulasan' => $lokasi->ulasan->map(function ($ulasan) {
                return [
                    'user' => $ulasan->user ? $ulasan->user->name : 'Anonim',
                    'photo_profile' => $ulasan->user ? $ulasan->user->profile_photo_path : 'Anonim',
                    'rating' => $ulasan->rating,
                    'komentar' => $ulasan->komentar,
                    'photo' => $ulasan->photo, // Sertakan foto ulasan jika ada
                ];
            }),
        ];

        return ResponseFormatter::success($response, 'Detail lokasi beserta rating dan ulasan berhasil diambil');
    }
}
