<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistrationConfirmation extends Controller
{
    public function index()
    {

           $user = User::where('confirmation_token', \request('token'))->first();
               if (! $user) {
                   return redirect('/threads')
                       ->with('flash', 'Unknown token');
               }

               $user->confirm();

              return redirect('/threads')
              ->with('flash', 'Email confirmed successfully');

        //dd($user);
    }
}
