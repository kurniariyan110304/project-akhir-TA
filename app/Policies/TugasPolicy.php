<?php

namespace App\Policies;

use App\Models\Tugas;
use App\Models\User;

class TugasPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'dosen', 'mahasiswa']);
    }

    public function view(User $user, Tugas $tugas): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'dosen') {
            return $tugas->kelas?->dosen_id === $user->dosen?->id;
        }

        if ($user->role === 'mahasiswa') {
            return $user->mahasiswa
                && $tugas->kelas
                && $tugas->kelas->mahasiswa()
                    ->where('mahasiswa.nim', $user->mahasiswa->nim)
                    ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'dosen']);
    }

    public function update(User $user, Tugas $tugas): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'dosen') {
            return $tugas->kelas?->dosen_id === $user->dosen?->id;
        }

        return false;
    }

    public function delete(User $user, Tugas $tugas): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'dosen') {
            return $tugas->kelas?->dosen_id === $user->dosen?->id;
        }

        return false;
    }

    public function restore(User $user, Tugas $tugas): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Tugas $tugas): bool
    {
        return $user->role === 'admin';
    }
}