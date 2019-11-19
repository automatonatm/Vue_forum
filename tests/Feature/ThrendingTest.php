<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThrendingTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->trending = new Trending();
        $this->trending->reset();

    }

    use DatabaseMigrations;

    public function test_a_thread_increments_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());
        $thread = create(Thread::class);
       // $this->call('GET', '/threads/channel/'.$this->thread->channel->slug.'/'.$this->thread->id');

        $this->call('GET', '/threads/channel/'.$thread->channel->slug.'/'.$thread->slug);
        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, ($trending[0])->title);



    }
}
