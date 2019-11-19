<?php


namespace App;


trait Favoritable
{


    public static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }


    public function favorite()
    {
        $attribute = ['user_id' => auth()->id()];
        if(!$this->favorites()->where($attribute)->exists()) {
             return $this->favorites()->create($attribute);
        }

    }

    public function unfavorite()
    {
        $attribute = ['user_id' => auth()->id()];
           return $this->favorites()->where($attribute)->get()->each->delete();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}