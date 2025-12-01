<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas_project';
    
    // Kolom yang bisa diisi
    protected $fillable = [
        'kategori',
        'semester',
        'deskripsi',
        'kelas_id',
        'mulai',
        'akhir',
        'kategori_project_id',
    ];
}