<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Matakuliah;
use App\Models\Dosen;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'semester',
        'kode',
        'matakuliah_id',
        'dosen_id',
        'ruang',
        'jam',
        'hari',
    ];

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class, 'matakuliah_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(
            Mahasiswa::class,
            'kelas_mahasiswa',
            'kelas_id',
            'mahasiswa_id'
        );
    }
}