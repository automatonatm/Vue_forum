<?php

namespace App\Policies;

use App\Thread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }





    /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\User  $user
     * @param  \App\User  $SignedInUser
     * @return mixed
     */
    public function update(User $user)
    {
        return Auth::id() === $user->id;
    }
}
