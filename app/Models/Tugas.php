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

    public function kelas()
    {
        // sesuaikan foreign key & model Kelas
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kategoriProject()
    {
        // sesuaikan foreign key & model kategori project
        // return $this->belongsTo(KategoriProject::class, 'kategori_project_id');
        // kalau nama modelnya Kategori saja:
        return $this->belongsTo(Kategori::class, 'kategori_project_id');
    }
}