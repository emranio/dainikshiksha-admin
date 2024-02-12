<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'editor', 'reporter', 'subscriber']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Poll $comment): bool
    {
        return $user->hasRole(['admin', 'editor', 'reporter', 'subscriber']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'editor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Poll $comment): bool
    {
        return $user->hasRole(['admin', 'editor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Poll $comment): bool
    {
        return $user->hasRole(['admin', 'editor']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Poll $comment): bool
    {
        return $user->hasRole(['admin', 'editor']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Poll $comment): bool
    {
        return $user->hasRole(['admin', 'editor']);
    }
}
