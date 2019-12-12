<?php

use App\Channel;
use Faker\Generator as Faker;


$factory->define(\App\Channel::class, function (Faker $faker) {
    $name  = $faker->word;
    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});



$factory->define(\App\Thread::class, function (Faker $faker) {
    $title =  $faker->sentence;
    return [
        'user_id' => function () {
           return factory(\App\User::class)->create()->id;
        },
        'channel_id' => function() {
            return factory(Channel::class)->create()->id;
        },
        'title' => $title,
        'body' => $faker->paragraph(10),
        'slug' => str_slug($title),
        'locked' => false

    ];
});


$factory->define('App\Reply', function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'thread_id' => function () {
            return factory(\App\Thread
            ::class)->create()->id;
        },

        'body' => $faker->paragraph(3)
    ];
});


$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [

        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
                return auth()->id() ?: factory(\App\User::class)->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']

    ];
});





