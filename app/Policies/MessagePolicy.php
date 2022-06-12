<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MessagePolicy
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
     * @param Message $message
     * @return bool
     */
    public function view(User $user, Message $message)
    {
        return $user->id === $message->getSenderId();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->role !== null;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function update(User $user, Message $message)
    {
        return $user->id === $message->getSenderId();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function delete(User $user, Message $message)
    {
        return $user->id === $message->getSenderId();
    }
}
