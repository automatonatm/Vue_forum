<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Types\Boolean;

class User extends Authenticatable
{
    use Notifiable;

    protected  $with = ['activity'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    protected  $casts = [
        'confirmed' => 'boolean'
    ];


    public function confirm()
    {

        $this->confirmed =  true;
        $this->confirmation_token =  null;
        $this->save();
    }


    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();

    }

    public function activity()
    {

        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {

        cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }


    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }


    public function avatar()
    {
        return $this->avatar_path ?: 'avatars/default.png';

    }

/*
    public function getAvatarPathAttribute($avatar)
    {
        return asset('/storage'.$avatar ?: '/storage/avatars/default.png');
    }*/

    public function isAdmin()
    {
        return in_array($this->name, [
            'Automaton',
            'Angle'
        ]);
    }
    
    


}

