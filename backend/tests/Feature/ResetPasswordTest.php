<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    protected $token = '';
    protected $email = '';
    

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that an existing user can reset password
     */
    public function test_that_an_existing_user_can_reset_password(): void
    {
        //$this->withoutExceptionHandling();
        $this->sendResetPassword();
        
        $response = $this->putJson("{$this->apiBase}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    /**
     * Testing that reset password must be required
     */
    public function test_that_reset_password_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $this->sendResetPassword();
        
        $response = $this->putJson("{$this->apiBase}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => '',
            'password_confirmation' => 'newpassword'
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
    }

    /**
     * Testing that reset password must be confirmed
     */
    public function test_that_reset_password_must_be_confirmed(): void
    {
        //$this->withoutExceptionHandling();
        $this->sendResetPassword();
        
        $response = $this->putJson("{$this->apiBase}/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => ''
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field confirmation does not match.']]]);
    }

    /**
     * Testing that token must be a valid token
     */
    public function test_that_token_must_be_a_valid_token(): void
    {
        //$this->withoutExceptionHandling();
        $this->sendResetPassword();
        
        $response = $this->putJson("{$this->apiBase}/reset-password?token={$this->token}notatoken", [
            'email' => $this->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);
        $response->assertStatus(500);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'Invalid token']);
    }

    /**
     * Testing that reset password email must be required
     */
    public function test_that_reset_password_email_must_be_required(): void
    {
        $data = ['email' => ''];

        $response = $this->postJson("{$this->apiBase}/reset-password", $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field is required.']]]);
    }

    /**
     * Testing that reset password email is valid
     */
    public function test_that_reset_password_email_is_valid(): void
    {
        $data = ['email' => 'notemail'];

        $response = $this->postJson("{$this->apiBase}/reset-password", $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field must be a valid email address.']]]);
    }

    /**
     * Testing that reset password email exists
     */
    public function test_that_reset_password_email_exists(): void
    {
        $data = ['email' => 'notexistingemail@example.com'];

        $response = $this->postJson("{$this->apiBase}/reset-password", $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The selected email is invalid.']]]);
    }

    /**
     * Testing that email must be associated with the token
     */
    public function test_that_email_must_be_associated_with_the_token(): void
    {
         //$this->withoutExceptionHandling();
         $this->sendResetPassword();
        
         $response = $this->putJson("{$this->apiBase}/reset-password?token={$this->token}", [
             'email' => 'fake@email.com',
             'password' => 'newpassword',
             'password_confirmation' => 'newpassword'
         ]);
         $response->assertStatus(500);
         $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
         $response->assertJsonFragment(['message' => 'Invalid email']);
    }

    public function sendResetPassword(){
        Notification::fake();
        $data = ['email' => 'example@example.com'];

        $response = $this->postJson("{$this->apiBase}/reset-password", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'OK']);
        $user = User::find(1);
        Notification::assertSentTo([$user], function (ResetPasswordNotification $notification) {
            $url = $notification->url;
            $parts = parse_url($url);
            parse_str($parts['query'], $query);
            $this->token = $query['token'];
            $this->email = $query['email'];
            return str_contains($url, 'http://front.app/reset-password?token=');
        });
    }
}
