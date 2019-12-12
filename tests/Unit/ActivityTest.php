<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{

    use DatabaseMigrations;

    public function test_it_records_activity_when_a_user_records_a_thread()
    {
        $this->logedIn();
        $thread = create(Thread::class);
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

       $activity =  Activity::first();

      // dd($activity);

       $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_it_record_activity_when_a_reply_is_created()
    {
        $this->logedIn();
        $reply = create(Reply::class);

        //dd(Activity::all());


        $this->assertEquals(3, Activity::count());

    }

    public function test_can_fetch_a_feed_for_any_user()
    {

        //$this->withoutExceptionHandling();
        $this->logedIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);



        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

     $feed = Activity::feed(auth()->user(), 50);


        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
   
}
