<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsdosKelas extends Model
{
    protected $table = 'asdos_kelas';

    public $timestamps = false;

    protected $fillable = [
        'asdos_id',
        'kelas_id',
    ];

    public function asdos(): BelongsTo
    {
        return $this->belongsTo(Asdos::class, 'asdos_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}