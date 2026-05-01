<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas_project';

    public $timestamps = false;

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
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kategoriProject()
    {
        return $this->belongsTo(Kategori::class, 'kategori_project_id');
    }
}