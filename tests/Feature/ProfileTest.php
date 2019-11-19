<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    Use DatabaseMigrations;
    public function test_a_user_has_a_profile()
    {

       $this->withoutExceptionHandling();
        $user = create(User::class);
        $this->get("/profiles/{$user->name}")
        ->assertSee($user->name);
   }

    public function test_display_all_post_created_by_associated_user()
    {
       $this->logedIn();
        $this->withoutExceptionHandling();

        $user = auth()->user();

        //$user = create(User::class);
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
   }
}
