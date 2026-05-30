<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori_project';

    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'kategori_project_id');
    }
}