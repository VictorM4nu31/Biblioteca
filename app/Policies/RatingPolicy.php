<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Determine if the user can update the rating.
     */
    public function update(User $user, Rating $rating): bool
    {
        // Solo el dueño puede editar su calificación
        return $rating->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the rating.
     */
    public function delete(User $user, Rating $rating): bool
    {
        // El dueño o un moderador pueden eliminar
        return $rating->user_id === $user->id || $user->can('moderate ratings');
    }
}
