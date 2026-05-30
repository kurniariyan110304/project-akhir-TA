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
        return $user->role === 'admin';
    }

    public function view(User $user, Tugas $tugas): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Tugas $tugas): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Tugas $tugas): bool
    {
        return $user->role === 'admin';
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