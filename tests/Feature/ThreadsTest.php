<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use phpDocumentor\Reflection\Types\Parent_;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }




    public function test_a_user_can_view_all_threads()
    {
        $this->withoutExceptionHandling();


        $response = $this->get('/threads');
        $response->assertStatus(200);




    }

    public function test_a_user_can_view_a_single_thread()
    {
        $this->withoutExceptionHandling();

        $response = $this->get($this->thread->path())
            ->assertSee($this->thread->title);

    }

    public function test_a_user_can_read_replies_associated_to_a_thread()
    {
        //giving a thread
         $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        // And the thread includes replies
           $response = $this->get($this->thread->path());
         $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        // See Replies when we visit a reply page
    }

    public function test_a_user_can_filter_thread_according_to_channel()
    {

        $this->withoutExceptionHandling();
        $channel = create(Channel::class);
        $thread_in_channel = create(Thread::class, ['channel_id' => $channel->id]);
        $thread_not_in_channel = create(Thread::class);



        $this->get('/threads/'.$channel->slug)
            ->assertSee($thread_in_channel->title)
            ->assertDontSee($thread_not_in_channel->title);
    }

    public function test_a_user_can_filter_thread_by_any_username()
    {
        $this->withoutExceptionHandling();
        $this->logedIn(create(User::class, ['name' => 'automaton']));
        $thread_by_automaton = create(Thread::class, ['user_id' => auth()->id()], 2);
        $thread_not_by_automaton = create(Thread::class);

        $this->get('/threads?by=automaton')
            //->assertSee($thread_by_automaton->title)
            ->assertDontSee($thread_not_by_automaton->title);
    }

    public function test_a_user_can_filter_thread_by_popularity()
    {
        //given 3 threads
        // with 2 replies, 3 replies and 0 replies respectively

        $thread_with_two_replies = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread_with_two_replies->id], 2);


        $thread_with_three_replies = create(Thread::class);

        create(Reply::class, ['thread_id' => $thread_with_three_replies->id], 3);

        $thread_with_no_replies = $this->thread;

        //when i filter by popularity
        $response = $this->getJson('/threads?popular=1')->json();
        //Then they should be returned from most replies to list

       $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count') );


    }

    public function test_a_user_can_filter_thread_by_those_that_are_unanswered()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' =>$thread->id]);
        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);

    }

    

    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id], 2);
        $response =  $this->getJson(route('reply.fetch', ['channel'=>$thread->channel->slug, 'thread'=>$thread->slug]))->json();
       // dd($response);
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    public function test_a_thread_records_each_view()
    {

        $thread = make(Thread::class, ['id' => 1]);

        $thread->resetVisits();

        $this->assertSame(0, $thread->views());

        $thread->recordViews();

        $this->assertEquals(1, $thread->views());

        $thread->recordViews();

        $this->assertEquals(2, $thread->views());


    }

    
}
