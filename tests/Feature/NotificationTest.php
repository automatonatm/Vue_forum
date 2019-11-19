<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->logedIn();



    }

    use DatabaseMigrations;


    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_current_user()
    {

        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->fresh()->notifications);


       $thread->addReply([
           'user_id' => auth()->id(),
           'body' => 'Some Reply'
       ]);


        $this->assertCount(0, auth()->user()->fresh()->notifications);


        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some Reply'
        ]);

      //  dd(auth()->user()->fresh()->unreadNotifications);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
   }


    public function test_a_user_fetch_their_unread_notifications()
    {


        create(DatabaseNotification::class);

        $response = $this->getJson(route('noty.fetch',
                ['user' => auth()->user()->name])
        )->json();

        $this->assertCount(1, $response);

   }

    public function test_a_user_can_mark_a_notification_as_read()
    {

        create(DatabaseNotification::class);

        $this->assertCount(1, auth()->user()->unreadNotifications);


        $this->delete(route('noty.destroy',
            ['user' => auth()->user()->name, 'notification' => auth()->user()->unreadNotifications->first()->id])
        );


        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);

    }
}
