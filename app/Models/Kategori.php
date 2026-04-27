<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    public $timestamps = true;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];
}