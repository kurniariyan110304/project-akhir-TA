<?php

namespace App\Policies;

use App\Models\Kategori;
use App\Models\User;

class KategoriPolicy
{
    /**
     * Admin full akses
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Lihat semua kategori
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'dosen';
    }

    /**
     * Lihat detail kategori
     */
    public function view(User $user, Kategori $kategori): bool
    {
        return $user->role === 'dosen';
    }

    /**
     * Tambah kategori
     */
    public function create(User $user): bool
    {
        return $user->role === 'dosen';
    }

    /**
     * Edit kategori
     */
    public function update(User $user, Kategori $kategori): bool
    {
        return $user->role === 'dosen';
    }

    /**
     * Hapus kategori (tidak boleh untuk dosen)
     */
    public function delete(User $user, Kategori $kategori): bool
    {
        return false;
    }

    public function restore(User $user, Kategori $kategori): bool
    {
        return false;
    }

    public function forceDelete(User $user, Kategori $kategori): bool
    {
        return false;
    }
}