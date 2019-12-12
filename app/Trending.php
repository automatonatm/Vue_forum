<?php


namespace App;


use Redis;

class Trending
{


    public function cacheKey()
    {
        return  app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function get()
    {
        /* $trending = collect(Redis::zrevrange('trending_threads', 0, 9))->map(function ($thread) {
           return json_decode($thread);
       });*/

        return $trending = array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 9));

    }


    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => '/threads/channel/'.$thread->channel->slug.'/'.$thread->slug
        ]));
    }


    public function reset()
    {
        Redis::del($this->cacheKey());
    }

}