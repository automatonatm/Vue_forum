<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class ThreadHasNewReply
 * @package App\Events
 */
class ThreadHasNewReply
{
    use  SerializesModels;
    /**
     * @var Thread
     */
    public $thread;

    /**
     * @var
     */
    public $reply;

    /**
     * Create a new event instance.
     * @param Thread $thread
     * @param Reply $reply
     * @return void
     */
    public function __construct($thread, $reply)
    {
        //
        $this->thread = $thread;
        $this->reply = $reply;
    }


}
