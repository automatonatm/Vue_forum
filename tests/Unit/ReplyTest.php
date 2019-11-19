<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    public function test_a_reply_has_an_owner()
    {
        $reply = factory(Reply::class)->create();
        $this->assertInstanceOf(User::class, $reply->user);
    }

    public function test_a_reply_knows_if_it_was_just_published()
    {
        $reply = factory(Reply::class)->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());

    }

    public function test_a_reply_can_detect_all_mentioned_users()
    {
        $reply = create(Reply::class, [
            'body' => '@Jane Wants to talk to @John'
        ]);

        $this->assertEquals(['Jane', 'John'], $reply->mentionedUser());



    }

    public function test_wrap_mentioned_users_in_the_body_within_anchor_tags()
    {
        $reply = New Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);

        $this->assertEquals('Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body);
    }


    public function test_it_knows_if_it_is_the_best_reply()
    {
        $reply = create(Reply::class);
        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
    
    
}
