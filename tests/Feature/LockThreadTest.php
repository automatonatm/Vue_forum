<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadTest extends TestCase
{

    use DatabaseMigrations;

    public function test_non_admin_cannot_lock_thread()
    {
        $this->withExceptionHandling();
        $this->logedIn();
        $thread = create(Thread::class);

        //hit an end point to lock a thread

        $this->post(route('locked-thread.store', $thread), [
            'locked' => true
        ])->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);


    }

    public function test_admin_cant_lock_thread()
    {
       // $this->withoutExceptionHandling();
        $this->logedIn(create(User::class, [
            'email' => 'automaton@gmail.com'
        ]));

        $thread = create(Thread::class);

        //hit an end point to lock a thread

        $this->post(route('locked-thread.store', $thread), [
            'locked' => true
        ])->assertStatus(200);

        $this->assertTrue( !! $thread->fresh()->locked, 'failed asserting that the thread is locked');

    }

    public function test_admin_cant_unlock_thread()
    {
         $this->withoutExceptionHandling();
        $this->logedIn(create(User::class, [
            'name' => 'Automaton'
        ]));

        $thread = create(Thread::class, [
            'locked' => false
        ]);

        //hit an end point to lock a thread

        $this->delete(route('locked-thread.destroy', $thread))->assertStatus(200);

        //dd($thread);

        $this->assertFalse( $thread->fresh()->locked, 'failed asserting that the thread was unlocked');

    }

    public function test_once_a_thread_is_locked_it_can_no_longer_receive_reply()
    {
        $this->withoutExceptionHandling();
        $this->logedIn();
        $thread = create(Thread::class);

        $thread->lock();

        $this->post($thread->path().'/replies', [
            'body' => 'FooBar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

}
