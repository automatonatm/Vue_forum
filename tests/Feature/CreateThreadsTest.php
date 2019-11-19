<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;


    public function test_guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $thread = make(Thread::class);
        // dd($thread);
        $this->get('/threads/create')
            ->assertRedirect('/login');


        $this->post('/threads')
            ->assertRedirect('/login');

    }

    public function test_auth_user_must_confirm_their_email()
    {
        $user = factory(User::class)->state('unconfirmed')->create();
        $this->withExceptionHandling()->logedIn($user);
        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash');


    }

    public function test_an_authenticated_user_can_create_new_threads()
    {
        $this->withoutExceptionHandling();

        $this->logedIn();

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray())
            ->assertRedirect(route('threads.show', [$thread->channel->name, $thread->slug]));

    }

    public function test_a_thread_must_have_a_title()
    {
       $this->publishThread(['title' => null])
           ->assertSessionHasErrors('title');

    }

    public function test_a_thread_must_have_a_body_a_user_id_and_a_channel_id()
    {
        $this->publishThread(['body' => null, 'channel_id' => null, 'user_id' => null])
            ->assertSessionHasErrors('body')
            ->assertSessionHasErrors('channel_id');

    }

    public function test_a_thread_must_have_a_valid_channel()
    {

        $channel = factory(Channel::class, 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 55])
            ->assertSessionHasErrors('channel_id');

    }

    public function test_a_thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();
       $this->logedIn();

       create(Thread::class, [], 2);

        $thread =create(Thread::class, [
            'title' => 'foo title'
        ]);

       $this->assertEquals($thread->fresh()->slug, 'foo-title');


       $thread = $this->postJson('/threads', $thread->toArray())->json();

       //dd($thread);

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);

        
   }

    public function test_a_thread_that_ends_with_a_number_should_generate_a_proper_slug()
    {

        $this->withoutExceptionHandling();
        $this->logedIn();

        $thread =create(Thread::class, [
            'title' => 'some title 24',
        ]);



        $thread = $this->postJson('/threads', $thread->toArray())->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);



   }



    public function test_guest_cannot_delete_thread()
    {

        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);


        $this->delete(route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->id]))
            ->assertRedirect('/login');




    }

    public function test_thread_can_be_deleted_by_only_authorised_users()
    {

        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);


        $this->delete(route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->id]))
            ->assertRedirect();

        $this->logedIn();
        $this->delete(route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->slug]))
            ->assertStatus(403);
        
    }



    public function test_an_authorised_user_can_delete_a_thread()
    {
        $this->withoutExceptionHandling();
        $user = $this->logedIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        //dd($reply);

        $this->json('DELETE', route('threads.show', ['channel' => $thread->channel->slug, 'thread' =>$thread->slug]))
        ->assertStatus(204);

         $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

         $this->assertDatabaseMissing('replies', ['thread_id' => $reply->thread_id]);

         $this->assertDatabaseMissing('activities', [
             'subject_id' => $thread->id,
             'subject_type' => get_class($thread)
         ]);


        $this->assertEquals(0, Activity::count());
    }

    public function publishThread($overrides = [])
    {
        $this->logedIn();
        $thread = make(Thread::class, $overrides);

       return $this->post('/threads', $thread->toArray());
    }





}
