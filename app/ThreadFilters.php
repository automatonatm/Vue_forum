<?php

namespace App;



use Illuminate\Http\Request;

class ThreadFilters extends Filters {

    protected  $filters = ['by', 'popular', 'unanswered'];
    /*
     *Filters the thread b a given username
     * @param string $username
     * @return mixed
     *
     */


    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }


    /**
     * Filter threads according to the most popular thread
     * @return $this
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = null;
       return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }

}