<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'isi_story' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Menyimpan foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('stories', 'public'); // Simpan ke storage/public/stories
        }

        // Simpan story
        $story = Story::create([
            'user_id' => $request->user()->id,
            'isi_story' => $validated['isi_story'],
            'photo' => $photoPath,
        ]);

        // Menambahkan prefix 'storage/' pada path gambar jika ada
        if ($story->photo) {
            $story->photo_url = 'storage/' . ltrim($story->photo, 'public/'); // Menambahkan 'storage/' di depan path gambar
        } else {
            $story->photo_url = null; // Jika tidak ada foto, set photo_url menjadi null
        }

        // Hapus atribut 'photo' dari respons
        unset($story->photo);

        return ResponseFormatter::success($story, 'Story berhasil ditambahkan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $stories = Story::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Tambahkan prefix 'storage/' pada path gambar untuk setiap story
        $stories->getCollection()->transform(function ($story) {
            if ($story->photo) {
                $story->photo_url = 'storage/' . ltrim($story->photo, 'public/'); // Menambahkan 'storage/' di depan path gambar
            } else {
                $story->photo_url = null; // Jika tidak ada foto, set photo_url menjadi null
            }

            // Hapus atribut 'photo' dari respons
            unset($story->photo);
            
            return $story;
        });

        return ResponseFormatter::success(
            $stories,
            'Daftar story berhasil diambil'
        );
    }

    /**
     * Menampilkan story tertentu
     */
    public function show($id)
    {
        $story = Story::with('user')->find($id);

        if (!$story) {
            return ResponseFormatter::error(null, 'Story tidak ditemukan', 404);
        }

        // Menambahkan prefix 'storage/' pada path gambar jika ada
        if ($story->photo) {
            $story->photo_url = 'storage/' . ltrim($story->photo, 'public/'); // Menambahkan 'storage/' di depan path gambar
        } else {
            $story->photo_url = null; // Jika tidak ada foto, set photo_url menjadi null
        }

        // Hapus atribut 'photo' dari respons
        unset($story->photo);

        return ResponseFormatter::success($story, 'Story berhasil ditemukan');
    }
}
