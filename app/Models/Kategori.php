<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_project';

    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];
}