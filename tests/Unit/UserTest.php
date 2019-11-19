<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use DatabaseMigrations;
    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->logedIn();

    }

    public function test_a_user_can_fetch_their_most_recent_replies()
    {
        $user = create(User::class);
        $reply =  create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    public function test_a_user_can_determine_their_avatar_path()
    {

        $user = create(User::class);

        $this->assertEquals('avatars/default.png', $user->avatar());

        $user->avatar_path = 'avatar/me.jpg';

        $this->assertEquals('avatar/me.jpg', $user->avatar());

    }



}
