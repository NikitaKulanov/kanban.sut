<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization.
     *
     * @param User $user
     * @return void|bool
     */
    public function before(User $user)
    {
        if ($user->role === 'administrator') {
            return true;
        }
    }

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
     * @param File $file
     * @return bool
     */
    public function view(User $user, File $file)
    {
        return $user->id === $file->getSenderId();
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
     * @param File $file
     * @return Response|bool
     */
    public function update(User $user, File $file)
    {
        return $user->id === $file->getSenderId();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param File $file
     * @return Response|bool
     */
    public function delete(User $user, File $file)
    {
        return $user->id === $file->getSenderId();
    }
}
