<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

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
        'user_id',
    ];

    protected static function booted(): void
    {
        static::saved(function (Dosen $dosen) {
            if ($dosen->user_id && $dosen->nidn) {
                $dosen->user()->update([
                    'password' => Hash::make($dosen->nidn),
                    'role' => 'dosen',
                    'nidn' => $dosen->nidn,
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

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }
}