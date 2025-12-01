<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

        // Kolom yang bisa diisi
        protected $fillable = [
            'nim',
            'nama',
            'jk',
            'tmp_lahir',
            'tgl_lahir',
            'email',
            'tgl_lahir',
            'thn_masuk',
            'prodi_id',
            'user_id',
        ];

}