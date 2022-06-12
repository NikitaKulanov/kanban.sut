<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function view(User $user, Link $link)
    {
        return $user->id === $link->getSenderId();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role !== null;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function update(User $user, Link $link)
    {
        return $user->id === $link->getSenderId();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function delete(User $user, Link $link)
    {
        return $user->id === $link->getSenderId();
    }
}
