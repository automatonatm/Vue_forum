<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{

    /**
     * AvatarController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {

       // dd(request()->all());

        $this->validate(request(), [
            'avatar' => 'required|image'
        ]);



        auth()->user()->update([
            'avatar_path' =>  $request->file('avatar')->store('avatars')
        ]);

        return response([], 204);
    }
}
