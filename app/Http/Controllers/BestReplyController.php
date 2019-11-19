<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    public function store(Reply $reply)
    {

        ///abort_if($reply->thread->user_id !== auth()->id(), 401);
        $this->authorize('update', $reply->thread);


        $reply->thread->MarkBestReply($reply);

    }
}
