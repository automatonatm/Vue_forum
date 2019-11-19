<?php


namespace App;


trait RecordsViews
{

    public function visitsCacheKey()
    {
        return "threads.($this->id).visits";
    }

    public function resetVisits()
    {
        \Redis::del($this->visitsCacheKey());
        return $this;
    }

    public function views()
    {
        return \Redis::get($this->visitsCacheKey()) ?? 0;
    }


    public function recordViews()
    {
        \Redis::incr($this->visitsCacheKey());
        return $this;
    }
}