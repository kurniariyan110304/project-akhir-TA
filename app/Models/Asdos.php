<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class Asdos extends Model
{
    protected $table = 'asdos';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'mahasiswa_nim',
        'aktif',
    ];

    protected static function booted(): void
    {
        static::saved(function (Asdos $asdos) {
            if ($asdos->user_id && $asdos->mahasiswa_nim) {
                $asdos->user()->update([
                    'role' => 'asdos',
                    'nim' => $asdos->mahasiswa_nim,
                    'password' => Hash::make($asdos->mahasiswa_nim),
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(
            Kelas::class,
            'asdos_kelas',
            'asdos_id',
            'kelas_id'
        );
    }
}