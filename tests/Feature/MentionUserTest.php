<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
   use DatabaseMigrations;


    public function test_mentioned_users_in_a_reply_are_notified()
    {

        $this->withoutExceptionHandling();

        //Given we hve a logged in user John Doe

        $john = create(User::class, ['name' => 'John']);
        $this->logedIn($john);

        //And another user Jane Doe
        $jane = create(User::class, ['name' => 'Jane']);

        $thread = create(Thread::class);

        //And John replies and mentions Jane
        $reply = make(Reply::class, [
            'body' => '@Jane look at this. Also @FrankDoe',
            'thread_id' => $thread->id
        ]);

        $this->json('post', route('reply.store', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]), $reply->toArray());

        $this->assertCount(1, $jane->notifications);

   }

    public function test_fetch_all_mentioned_users_starting_with_the_the_given_characters()
    {
        create(User::class, ['name' => 'johndoe']);
        create(User::class, ['name' => 'janeMark']);
        create(User::class, ['name' => 'johnPaul']);

        $result = $this->json('GET', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $result->json());
   }
   

}
