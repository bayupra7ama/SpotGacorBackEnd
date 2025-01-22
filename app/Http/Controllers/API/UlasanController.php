<?php

namespace App\Http\Controllers\API;

use App\Models\Lokasi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UlasanController extends Controller
{
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'rating' => 'required|integer|between:1,5',
        'komentar' => 'nullable|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Ambil lokasi berdasarkan ID
    $lokasi = Lokasi::find($request->input('lokasi_id'));
    if (!$lokasi) {
        return ResponseFormatter::error(null, 'Lokasi tidak ditemukan', 404);
    }

    // Periksa apakah pengguna sudah memberikan ulasan untuk lokasi ini
    $existingUlasan = $lokasi->ulasan()->where('user_id', $request->user()->id)->first();
    if ($existingUlasan) {
        return ResponseFormatter::error(null, 'Anda sudah memberikan ulasan untuk lokasi ini', 400);
    }

    // Menyimpan foto ulasan jika ada
    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('ulasan', 'public'); // Simpan di folder public/ulasan
    }

    // Simpan ulasan baru
    $ulasan = $lokasi->ulasan()->create([
        'user_id' => $request->user()->id,
        'rating' => $request->input('rating'),
        'komentar' => $request->input('komentar'),
        'photo' => $photoPath, // Menyimpan path file yang relatif
    ]);

    return ResponseFormatter::success(
        $ulasan,
        'Ulasan berhasil disimpan'
    );
}

    
}
