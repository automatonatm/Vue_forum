<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockThreadController extends Controller
{

    /**
     * LockThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('must-be-admin');
    }

    public function store(Thread $thread)
    {

            $thread->lock();
    }


    public function destroy(Thread $thread)
    {
        $thread->unLocked();
    }


}
