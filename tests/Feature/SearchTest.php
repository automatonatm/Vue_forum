<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        $this->withoutExceptionHandling();
        $search = 'foobar';
        create(Thread::class, [], 2);
        $desired_threads = create(Thread::class, ['body' => "A thread with  {$search} tern"], 2);

        do {
            $response =  $this->getJson("/threads/search?q={$search}")->json()['data'];

        }while(empty($response));


       $this->assertCount(2, $response);


       // $desired_threads->unsearchable();

        Thread::latest()->take(4)->unsearchable();



    }
   
}
