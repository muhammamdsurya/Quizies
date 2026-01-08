<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * SUPER ROLE
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'kaprodi') {
            return true;
        }

        return null;
    }

    /**
     * SEMUA ROLE BISA MELIHAT LIST
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['dosen', 'mahasiswa']);
    }

    /**
     * SEMUA ROLE BISA VIEW DETAIL
     */
    public function view(User $user, User $model): bool
    {
        return in_array($user->role, ['dosen', 'mahasiswa']);
    }

    /**
     * CRUD KHUSUS KAPRODI
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }
}
