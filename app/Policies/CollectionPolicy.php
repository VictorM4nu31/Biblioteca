<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
{
    /**
     * Determine if the user can view the collection.
     */
    public function view(User $user, Collection $collection): bool
    {
        // Puede ver si es pública o es el dueño
        return $collection->is_public || $collection->user_id === $user->id;
    }

    /**
     * Determine if the user can create collections.
     */
    public function create(User $user): bool
    {
        return $user->can('create collections');
    }

    /**
     * Determine if the user can update the collection.
     */
    public function update(User $user, Collection $collection): bool
    {
        // Solo el dueño puede editar
        return $collection->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the collection.
     */
    public function delete(User $user, Collection $collection): bool
    {
        // Solo el dueño puede eliminar
        return $collection->user_id === $user->id;
    }
}
