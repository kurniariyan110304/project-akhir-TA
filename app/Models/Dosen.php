<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nidn',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'prodi_id',
        'user_id',
    ];

}