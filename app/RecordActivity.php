<?php


namespace App;


use function PHPSTORM_META\type;

trait RecordActivity
{

    protected static function bootRecordActivity()
    {
        /*
        static::created(function ($activity) {
            $activity->recordActivity('created');
        });*/

        // even guest
        if (auth()->guest()) return;

        foreach (static::getRecordEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });

        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });



    }

    public static function getRecordEvents()
    {
      return ['created', 'updated'];
    }



    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);

    }

    public function activity()
    {
            return $this->morphMany(Activity::class, 'subject');
    }
}