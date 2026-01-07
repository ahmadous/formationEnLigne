<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpisodePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Episode $episode)
    {
        // Allow if episode is active or user owns the course
        return $episode->status === 'active' || $user->id === $episode->course->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasRole('instructor') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Episode $episode)
    {
        return $user->id === $episode->course->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Episode $episode)
    {
        return $user->id === $episode->course->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage status (pause/block).
     */
    public function manageStatus(User $user, Episode $episode)
    {
        return $user->id === $episode->course->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can report the model.
     */
    public function report(User $user, Episode $episode)
    {
        return $user->hasRole('student') && $user->id !== $episode->course->user_id;
    }
}
