<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected  $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    public function test_a_thread_has_replies()
    {

        $this->assertInstanceOf(Collection::class, $this->thread->replies);

    }

    public function test_a_thread_has_a_user()
    {


        $this->assertInstanceOf(User::class, $this->thread->user);
    }

    public function test_a_user_can_reply_to_a_thread()
    {
        $reply = $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        //dd($reply);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {

        Notification::fake();
        $this->logedIn()
            ->thread
            ->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => 444
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }



    public function test_a_thread_belongs_to_channel()
    {
        $this->withoutExceptionHandling();
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $this->withoutExceptionHandling();
        //given we have a thread
        $thread = create(Thread::class);

        //An an authenticated user
        //$user = $this->logedIn();
        //// when the user subscribes to the thread

        $thread->subscribe($user_id = 1);

        //The we should be able to fetch all thread that a user has subscribed to

        $this->assertEquals(1,
            $thread->subscriptions()->where('user_id', $user_id)->count()
        );

    }

    public function test_a_thread_can_be_unsubscribe_from()
    {

        $this->withoutExceptionHandling();

        $thread = create(Thread::class);

        // A user who is subscribed to the thread
        $thread->subscribe($user_id  = 1);

        $thread->unsubscribe($user_id);

        $this->assertCount(0, $thread->subscriptions);
    }

    public function test_it_knows_if_the_auth_user_is_subscribed_to_it()
    {

        $this->withoutExceptionHandling();

        $thread = create(Thread::class);

        $this->logedIn();

        $this->assertFalse($thread->isSubscribedTo);

        // A user who is subscribed to the thread
        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    public function test_a_thread_can_check_if_auth_user_has_read_all_replies()
    {

        $this->logedIn();
        $thread = create(Thread::class);

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);


            $this->assertFalse($thread->hasUpdatesFor($user));
        });


    }


}
