<?php

namespace App\Models;

use App\Models\User;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'user_id',
        'ulasan_id',
        'nama_tempat','alamat','lat','long','image_paths',
        'rute','perlengkapan','umpan','jenis_ikan','created_by'
       
    ];

    public function user()  {
        return $this->hasOne(User::class, 'id','user_id');
    }

   public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'lokasi_id', 'id');
    }
}
