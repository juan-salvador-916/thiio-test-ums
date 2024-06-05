<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated admin user can create a user
     */
    public function test_that_an_authenticated_admin_user_can_create_a_user(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.created'));
        $response->assertJsonFragment([
            'message' => 'User Created',
            'data' => [
                'user' => [
                    'id' => 2,
                    'email' => 'juan@gmail.com',
                    'name' => 'Juan Salvador',
                    'last_name' => 'Hernandez',
                    'role' => 'NORMAL'
                ]
            ],
            'status' => config('http_constants.created'),
            'errors' => []
        ]);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'email' => 'juan@gmail.com',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ]);
    }

    /**
     * Testing that new user password must be string.
     */
    public function test_that_new_user_password_must_be_string(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'role' => 'NORMAL',
            'password' => 12345678
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be a string.']]]);
    }

    /**
     * Testing that new user password must be at least 8 characters
     */
    public function test_that_new_user_password_must_be_at_least_8_characters(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'ju',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be at least 8 characters.']]]);
    }

    /**
     * Testing that new user name must be string.
     */
    public function test_that_new_user_name_must_be_string(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 12345678,
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be a string.']]]);
    }

    /**
     * Testing that new user name must be at least 2 characters
     */
    public function test_that_new_user_name_must_be_at_least_2_characters(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'J',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be at least 2 characters.']]]);
    }

    /**
     * Testing that new user last name must be string.
     */
    public function test_that_new_user_last_name_must_be_string(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 12345678,
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be a string.']]]);
    }

    /**
     * Testing that new user last name must be at least 2 characters
     */
    public function test_that_new_user_last_name_must_be_at_least_2_characters(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'H',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be at least 2 characters.']]]);
    }


    /**
     * Testing that new user email must be valid.
     */
    public function test_that_new_user_email_must_be_valid(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'asdasdasd',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field must be a valid email address.']]]);
    }

    /**
     * Testing that new user email must not match to an existing one
     */
    public function test_that_new_user_email_must_not_match_to_an_existing_one(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        User::factory()->create([
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $data = [
            'name' => 'Other User',
            'last_name' => 'Test',
            'email' => 'juan@gmail.com',
            'password' => 'test12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email has already been taken.']]]);
    }

    /**
     * Testing that new user role must be valid.
     */
    public function test_that_new_user_role_must_be_valid(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'asasas'
        ];

        $response = $this->apiAs($user_admin, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['role']]);
        $response->assertJsonFragment(['errors' => ['role' => ['The selected role is invalid.']]]);
    }

    /**
     * Testing that an un-authenticated admin user cannot create users.
     */
    public function test_that_an_un_authenticated_admin_user_cannot_create_users(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'ADMIN'
        ];

        $response = $this->postJson("{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }
}
