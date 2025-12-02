<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    public $timestamps = false;


    protected $fillable = [
        'semester',
        'kode',
        'matakuliah_id',
        'dosen_id',
        'ruang',
        'jam',
        'hari',
    ];

    // Relasi opsional (kalau nanti ada model Matakuliah & Dosen)
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}