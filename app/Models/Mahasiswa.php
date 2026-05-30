<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Prodi;


class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nama',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'email',
        'thn_masuk',
        'prodi_id',
        'user_id',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function kelas(): BelongsToMany
{
    return $this->belongsToMany(
        Kelas::class,
        'kelas_mahasiswa',
        'mahasiswa_id',
        'kelas_id'
    );
}

}