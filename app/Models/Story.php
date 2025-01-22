<?php

namespace App\Models;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'isi_story',
        'photo'

    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'id', 'lokasi_id');
    }
}
