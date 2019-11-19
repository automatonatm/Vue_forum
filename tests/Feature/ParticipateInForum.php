<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForum extends TestCase
{
    use DatabaseMigrations;

    /*
     @test
    */
    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();

    }


    public function test_an_authenticated_user_may_participate_in_a_forum_thread()
    {
        //given we hve a user
        $this->logedIn();

        $threads = create(Thread::class);


        $reply = make(Reply::class);


        $this->post('/threads/channel/'.$threads->channel->slug.'/'.$threads->id.'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body'=> $reply->body]);

      // dd($threads::find(1)->replies_count);

        $this->assertDatabaseHas('threads', ['replies_count' => 1]);

        $this->assertEquals(1, $threads::find(1)->replies_count);

        //dd($threads->replies);

       /* $this->get(route('threads.show', [$threads->channel->slug, $threads->id]))
            ->assertSee($reply->body);*/



    }


    public function test_a_reply_must_have_body_and_a_channel()
    {
        //$this->withoutExceptionHandling();

        $this->logedIn();

        $threads = create(Thread::class);

        $reply = make(Reply::class, ['body' => null]);

        $this->post('/threads/channel/'.$threads->channel->slug.'/'.$threads->id.'/replies',$reply->toArray())
            ->assertSessionHasErrors('body');



    }

    public function test_unauthorised_users_cannot_delete_reply()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->logedIn()
            ->delete("/replies/{$reply->id}")
        ->assertStatus(403);
    }

    public function test_authorised_users_can_delete_reply()
    {
        $this->withoutExceptionHandling();
        $this->logedIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
       // dd($reply);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_authorised_users_can_update_replies()
    {

        $this->withoutExceptionHandling();
        $this->logedIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply_body = 'You been changed fool.';
        $this->patch("/replies/{$reply->id}",
            ['body' => $reply_body]);

        $this->assertDatabaseHas('replies',
            ['id' => $reply->id, 'body' => $reply_body]);


    }

    public function test_unauthorised_users_cannot_update_a_reply()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->patch("replies/{$reply->id}")
            ->assertRedirect('/login');


    }

    public function test_reply_that_contain_spam_not_be_created()
    {
        $this->withExceptionHandling();
        $this->logedIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

        //$this->expectException(\Exception::class);

        $this->post(route('reply.store', ['channel' => $thread->channel->slug, 'thread' => $thread->id]), $reply->toArray())
        ->assertStatus(422);

    }

    public function test_users_may_only_reply_a_maximum_of_once_per_minutes()
    {
        $this->logedIn();
        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => 'A friendly Reply'
        ]);

        $this->post(route('reply.store',
            ['channel' => $thread->channel->slug,
                'thread' => $thread->id]), $reply->toArray())
            ->assertStatus(200);

        $this->post(route('reply.store',
            ['channel' => $thread->channel->slug,
                'thread' => $thread->id]), $reply->toArray())
            ->assertStatus(422);
    }




}
