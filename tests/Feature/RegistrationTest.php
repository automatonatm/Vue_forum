<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYouEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_confirmation_email_is_sent_upon_registration()
    {
        $this->withExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'Automaton',
            'email' => 'mail@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

       event(new Registered(create(User::class)));

        Mail::assertQueued(PleaseConfirmYouEmail::class);

    }

    public function test_users_can_confirm_their_email_addresses()
    {

      $this->withExceptionHandling();
       $this->post('/register', [
           'name' => 'Automaton',
           'email' => 'mail@example.com',
           'password' => 'foobar',
           'password_confirmation' => 'foobar',
       ]);


       $user = User::whereName('Automaton')->first();

       $this->assertFalse($user->confirmed);
       $this->assertNotNull($user->confirmation_token);


      $response =  $this->get('/register/confirm?token='.$user->confirmation_token);

      tap($user->fresh(), function ($user) {
          $this->assertTrue($user->confirmed);
          $this->assertEmpty($user->confirmation_token);
      });



       $response->assertRedirect('/threads');



    }

    public function test_confirm_an_invalid_token()
    {
        $this->withoutExceptionHandling();
        $this->get(route('confirm', ['token' => 'invalid']))
        ->assertRedirect(route('threads'))
        ->assertSessionHas('flash', 'Unknown token');


    }



    
}
