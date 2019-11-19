<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{

    /**
     * UserNotificationsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
        
    }


    public function destroy(User $user, $notification_id)
    {
        auth()->user()->notifications()->findOrFail($notification_id)->markAsRead();
    }
}
