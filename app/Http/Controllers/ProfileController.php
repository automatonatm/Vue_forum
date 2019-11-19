<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function show(User $user)
    {

       // $this->authorize('update', $user);

        return view('profiles.show',
            [
                'profile_user' => $user,
                'activities' =>  Activity::feed($user) //delegated to a static method
            ]
        );

    }

}
