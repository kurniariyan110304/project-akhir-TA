<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kategoriProject(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_project_id');
    }

    public function projectMahasiswa(): HasMany
    {
        return $this->hasMany(ProjectMahasiswa::class, 'tugas_project_id');
    }
}