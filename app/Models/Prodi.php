<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    // Kolom yang bisa diisi
    protected $fillable = [
        'kode',
        'nama',
        'kaprodi',
    ];

    public $timestamps = false;
}