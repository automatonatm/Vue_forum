<?php
namespace  App\Entities;

use App\Activity;
use App\Events\ThreadHasNewReply;
use App\Events\ThreadRecievesNewReply;
use App\Notifications\ThreadWasUpdated;
use App\Reply;


trait  Threading {

    public function addReply($reply)
    {



        $reply = $this->replies()->create($reply);

        event(new ThreadRecievesNewReply($reply));

        return $reply;

    }



    public function hasUpdatesFor($user)
    {
        //Look in the cache for the proper reply
        //Compare that carbon instance with the $thread->updated_at

        $user = $user ?: auth()->user();
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }


    public function scopeFilter($query, $filters)
    {
        //this is coming from ThreadFilters
        return $filters->apply($query);
    }

    public function MarkBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }




}