<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';

    public $incrementing = false;

    protected $keyType = 'string';

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

    protected static function booted(): void
    {
        static::saved(function (Mahasiswa $mahasiswa) {
            if ($mahasiswa->user_id && $mahasiswa->nim) {
                $mahasiswa->user()->update([
                    'password' => Hash::make($mahasiswa->nim),
                    'role' => 'mahasiswa',
                    'nim' => $mahasiswa->nim,
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(
            Kelas::class,
            'kelas_mahasiswa',
            'mahasiswa_nim',
            'kelas_id',
            'nim',
            'id'
        )->withPivot('nilai_akhir');
    }

    public function projectMahasiswa(): HasMany
    {
        return $this->hasMany(ProjectMahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    public function kelompokProject(): HasMany
    {
        return $this->hasMany(KelompokProject::class, 'mahasiswa_nim', 'nim');
    }
}