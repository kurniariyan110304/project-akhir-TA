<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';

    public $timestamps = false;

    protected $fillable = [
        'nidn',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'prodi_id',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}