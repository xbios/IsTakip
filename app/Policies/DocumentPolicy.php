<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        return $user->isAdmin() || $document->created_by === $user->id || $document->related_type === 'global';
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Document $document): bool
    {
        return $user->isAdmin() || $document->created_by === $user->id;
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin() || $document->created_by === $user->id;
    }
}
