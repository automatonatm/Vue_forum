<?php

namespace App\Listeners;

use App\Events\ThreadRecievesNewReply;
use App\Notifications\YouWereMentioned;
use App\User;


class NotifyMentionedUsers
{


    /**
     * Handle the event.
     *
     * @param  ThreadRecievesNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievesNewReply $event)
    {

        $users = User::whereIn('name', $event->reply->mentionedUser())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });

//        $users = User::whereIn('name', $event->reply->mentionedUser)->get();
//
//        dd($users);
//
//        collect($event->reply->mentionedUser())
//            ->map(function ($name) {
//                return User::whereName($name)->first();
//            })
//            ->filter()
//            ->each(function ($user) use ($event) {
//                $user->notify(new YouWereMentioned($event->reply));
//            });


        /*foreach ($mentioned_user as $name) {
            if($user = User::whereName($name)->first()) {
                $user->notify(new YouWereMentioned($event->reply));
            }
        }*/
    }
}
