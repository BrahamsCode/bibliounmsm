<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function update(User $user, Book $book): bool
    {
        return $user->isAdmin() || $user->isLibrarian();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isLibrarian();
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->isAdmin() || $user->isLibrarian();
    }
}
