<?php

namespace App\Listeners;

use App\Events\ThreadRecievesNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{

    /**
     * Handle the event.
     *
     * @param  ThreadRecievesNewReply  $event
     * @return void
     *
     */

    public function handle(ThreadRecievesNewReply $event)
    {

        $event->reply->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
