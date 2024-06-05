<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that a user can register.
     */
    public function test_that_a_user_can_register(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password123',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];

        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.created'));
        $response->assertJsonStructure([
            'message', 
            'data' => [
                'user' => ['id','email','name','last_name','role']
            ], 
            'status', 
            'errors'
        ]);
        $response->assertJsonFragment([
            'message' => 'User Registered',
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
     * Testing that a registered user can login.
     */
    public function test_that_a_registered_user_can_login(): void
    {
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password123',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $registerResponse = $this->postJson("{$this->apiBase}/register-user", $data);

        $registerResponse->assertStatus(config('http_constants.created'));
        
        $dataLogin = [
            'email' => 'juan@gmail.com',
            'password' => 'password123',
        ];

        $responseLogin = $this->postJson("{$this->apiBase}/login", $dataLogin);
        $responseLogin->assertStatus(config('http_constants.ok'));
        $responseLogin->assertJsonStructure(['data' => ['token'], 'message', 'status', 'errors']);

    }

    /**
     * Testing that register email must be required
     */
    public function test_that_register_email_must_be_required(): void
    {
        //$this->withoutExceptionHandling();

        $data = [
            'email' => '',
            'password' => 'password123',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];

        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field is required.']]]);
    }

    /**
     * Testing that register email is valid
     */
    public function test_that_register_email_is_valid(): void
    {
        $data = [
            'email' => 'juan123',
            'password' => 'password123',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field must be a valid email address.']]]);
    }

    /**
     * Testing that register email is unique in database
     */
    public function test_that_register_email_must_be_unique_in_database(): void
    {
        //$this->withoutExceptionHandling();
        User::factory()->create([
            'email' => 'juan@gmail.com',
            'role' => 'NORMAL'
        ]);

        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password123',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email has already been taken.']]]);
    }


    /**
     * Testing that register password must be required
     */
    public function test_that_register_password_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'email' => 'juan@gmail.com',
            'password' => '',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field is required.']]]);
    }

    /**
     * Testing that register password must have at least 8 characters
     */
    public function test_that_register_password_must_have_at_least_8_characters(): void
    {
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'asdasd',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be at least 8 characters.']]]);
    }

    /**
     * Testing that register name must be required
     */
    public function test_that_register_name_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'name' => '',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field is required.']]]);
    }

    /**
     * Testing that register name must have at least 2 characters
     */
    public function test_that_register_name_must_have_at_least_2_characters(): void
    {
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'name' => 'J',
            'last_name' => 'Hernandez',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be at least 2 characters.']]]);
    }


    /**
     * Testing that register last name must be required
     */
    public function test_that_register_last_name_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'name' => 'Juan Salvador',
            'last_name' => '',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field is required.']]]);
    }

    /**
     * Testing that register last name must have at least 2 characters
     */
    public function test_that_register_last_name_must_have_at_least_2_characters(): void
    {
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'name' => 'Juan Salvador',
            'last_name' => 'H',
            'role' => 'NORMAL'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be at least 2 characters.']]]);
    }

    /**
     * Testing that register role value must be normal
     */
    public function test_that_register_role_value_must_be_normal(): void
    {
        $data = [
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'role' => 'ADMIN'
        ];
        $response = $this->postJson("{$this->apiBase}/register-user", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['role']]);
        $response->assertJsonFragment(['errors' => ['role' => ['The selected role is invalid.']]]);
    }
}
