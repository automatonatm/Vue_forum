<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    public function test_only_members_can_add_avatars()
    {
        //$this->withoutExceptionHandling();
         $this->json('POST', '/api/users/1/avatar')
         ->assertStatus(401);

    }

    public function test_a_valid_avatar_must_be_provided()
    {

        //$this->withoutExceptionHandling();
        $this->logedIn();
        $this->json('POST', '/api/users/'.auth()->id().'/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);

    }

    public function test_a_user_may_add_an_avatar_to_their_profile()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');
        $this->logedIn();

        $this->json('POST', '/api/users/'.auth()->id().'/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

       $this->assertEquals('avatars/'.$file->hashName(), auth()->user()->avatar_path);



        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }

    

}
