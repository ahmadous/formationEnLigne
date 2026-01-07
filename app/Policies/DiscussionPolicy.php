<?php

namespace App\Policies;

use App\Models\Discussion;
use App\Models\User;

class DiscussionPolicy
{
    /**
     * Determine whether the user can update the discussion.
     */
    public function update(User $user, Discussion $discussion): bool
    {
        return $user->id === $discussion->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the discussion.
     */
    public function delete(User $user, Discussion $discussion): bool
    {
        return $user->id === $discussion->user_id || $user->hasRole('admin');
    }
}
