<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{

    use DatabaseMigrations;

    public function test_a_thread_creator_can_mark_any_reply_as_best()
    {

        $this->withoutExceptionHandling();
        $this->logedIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-reply.store', ['reply' => $replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
        
   }

    public function test_only_thread_creator_may_mark_reply_as_best()
    {
        $this->logedIn();

        $thread = create(Thread::class,  ['user_id' => auth()->id()]);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->logedIn(create(User::class));

        $this->postJson(route('best-reply.store', ['reply' => $replies[1]->id]))
        ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());




   }
}
