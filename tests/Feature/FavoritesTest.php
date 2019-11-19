<?php

namespace Tests\Feature;

use App\Favorite;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_cannot_favourite_any_thing()
    {
        $this->withExceptionHandling();
        $this->post('/replies/1/favorites')
        ->assertRedirect('/login');
    }
    public function test_an_auth_user_can_favourite_any_reply()
    {


        $this->withoutExceptionHandling();
        $this->logedIn();
        $reply = create(Reply::class);

        $this->post('/replies/'.$reply->id.'/favorites');

        //dd(Favorite::all());

        $this->assertCount(1, $reply->favorites);

   }

    public function test_an_auth_user_may_only_favorite_a_reply_once()
    {
        $this->withoutExceptionHandling();
        $this->logedIn();
        $reply = create(Reply::class);

        try{
            $this->post('/replies/'.$reply->id.'/favorites');

            $this->post('/replies/'.$reply->id.'/favorites');
        } catch (\Exception $e) {
            $this->fail('You can\'t favoite more than once');
        }



        //dd(Favorite::all());

        $this->assertCount(1, $reply->favorites);
   }

    public function test_an_auth_user_can_unfavourite_any_reply()
    {


        $this->withoutExceptionHandling();
        $this->logedIn();
        $reply = create(Reply::class);

        $reply->favorite();

        $this->delete('/replies/'.$reply->id.'/favorites');

        $this->assertCount(0, $reply->favorites);

    }
}
