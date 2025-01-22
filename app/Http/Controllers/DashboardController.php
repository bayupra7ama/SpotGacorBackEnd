<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data lokasi
        $lokasi = Lokasi::paginate(10); // Mengambil 10 data per halaman

        // Kirim data ke tampilan
        return view('dashboard', compact('lokasi'));
    }
    public function create()
    {
        return view('lokasi.create'); // Tampilkan halaman form tambah lokasi
    }
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_tempat' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
                'perlengkapan' => 'required',
                'rute' => 'required',
                'umpan' => 'required|string|max:255',
                'jenis_ikan' => 'nullable|string|max:255',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Simpan lokasi baru ke database
            $lokasi = Lokasi::create([
                'user_id' => $request->user()->id,
                'nama_tempat' => $request->input('nama_tempat'),
                'alamat' => $request->input('alamat'),
                'lat' => $request->input('lat'),
                'long' => $request->input('long'),
                'jenis_ikan' => $request->input('jenis_ikan'),
                'created_by' => $request->user()->name,
                'perlengkapan' => $request->input('perlengkapan'),
                'rute' => $request->input('rute'),
                'umpan' => $request->input('umpan'),
            ]);

            // Menyimpan gambar
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('lokasi_photos', 'public');
                $url = Storage::url($path);
                $uploadedImages[] = $url;
            }

            // Update lokasi dengan URL gambar
            $lokasi->update(['image_paths' => $uploadedImages]);

            return redirect()->route('dashboard')->with('success', 'Lokasi berhasil disimpan!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal menyimpan lokasi: ' . $e->getMessage());

            // Redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Gagal menyimpan lokasi! ' . $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);

        return view('lokasi.edit', compact('lokasi'));
    }
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'perlengkapan' => 'nullable|string|max:1000',
            'rute' => 'nullable|string|max:1000',
            'umpan' => 'nullable|string|max:255',
            'jenis_ikan' => 'nullable|string|max:255',
        ]);

        // Temukan lokasi berdasarkan ID
        $lokasi = Lokasi::findOrFail($id);

        // Update data lokasi
        $lokasi->update([
            'nama_tempat' => $request->input('nama_tempat'),
            'alamat' => $request->input('alamat'),
            'lat' => $request->input('lat'),
            'long' => $request->input('long'),
            'perlengkapan' => $request->input('perlengkapan'),
            'rute' => $request->input('rute'),
            'umpan' => $request->input('umpan'),
            'jenis_ikan' => $request->input('jenis_ikan'),
        ]);

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Data lokasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);

        // Hapus lokasi dari database
        $lokasi->delete();

        // Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Data lokasi berhasil dihapus!');
    }
}
