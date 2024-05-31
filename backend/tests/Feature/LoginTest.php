<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that an existing user can login
     */
    public function test_that_an_existing_user_can_login(): void
    {
        //$this->withoutExceptionHandling();
        $credentials = ['email' => 'example@example.com', 'password' => 'password'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /**
     * Testing that an non-existing user cannot login
     */
    public function test_that_a_non_existing_user_cannot_login(): void
    {
        $credentials = ['email' => 'example@nonexisting.com', 'password' => 'password'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        //$response->dd();
        $response->assertStatus(401);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);
    }

    /**
     * Testing that email must be required
     */
    public function test_that_email_must_be_required(): void
    {
        $credentials = ['password' => 'password'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field is required.']]]);
    }

    /**
     * Testing that is valid email
     */
    public function test_that_is_valid_email(): void
    {
        $credentials = ['email' => 'asdasdasdasd', 'password' => 'password'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field must be a valid email address.']]]);
    }

    /**
     * Testing that email must be a string
     */
    public function test_that_email_must_be_a_string(): void
    {
        $credentials = ['email' => 1, 'password' => 'password'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => [
            'The email field must be a string.',
            'The email field must be a valid email address.'
        ]]]);
    }

    /**
     * Testing that password must be required
     */
    public function test_that_password_must_be_required(): void
    {
        $credentials = ['email' => 'example@nonexisting.com'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        //$response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field is required.']]]);
    }

    /**
     * Testing that password must have at least 8 characters
     */
    public function test_that_password_must_have_at_least_8_characters(): void
    {
        $credentials = ['email' => 'example@example.com', 'password' => 'asd'];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be at least 8 characters.']]]);
    }
}
