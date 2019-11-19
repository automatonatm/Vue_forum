<?php

namespace Tests\Feature;

use App\Thread;
use App\ThreadSubcription;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToTest extends TestCase
{
    
    use DatabaseMigrations;
    /**
     *
     * A basic test example.
     *
     * @return void
     */

    public function test_a_user_can_subscribe_to_threads()
    {

       $this->withoutExceptionHandling();
        $this->logedIn();
        $thread = create(Thread::class);
        $this->post(route('thread.subscribe',
            ['channel' => $thread->channel->slug, 'thread' => $thread->slug ])
        );

        $this->assertCount(1, $thread->fresh()->subscriptions);

   /*     $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here'
        ]);


        $this->assertCount(1, auth()->user()->fresh()->notifications);*/


    }

    public function test_a_user_can_unsubscribe_to_threads()
    {

        $this->withoutExceptionHandling();
        $this->logedIn();
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $this->post(route('thread.subscribe',
            ['channel' => $thread->channel->slug, 'thread' => $thread->slug ])
        );

        // Each time a reply is left live a new reply for the user

        $this->delete(route('subscribe.remove', ['channel' => $thread->channel->slug, 'thread' => $thread->slug ]));


        //$this->assertCount(1, auth()->user()->notifications);



        $this->assertCount(0, $thread->subscriptions);

    }

}
