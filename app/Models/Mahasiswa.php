<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

        // Kolom yang bisa diisi
        protected $fillable = [
            'nim',
            'nama',
            'jk', // kosongkan
            'tmp_lahir', //kosongkan
            'tgl_lahir', //kosongkan
            'email', //kosongkan
            'tgl_lahir', //kosongkan
            'thn_masuk',
            'prodi_id',
            'user_id', 
        ];

}