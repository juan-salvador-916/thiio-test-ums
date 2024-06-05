<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that an authenticated user can modify their data.
     */
    public function test_that_an_authenticated_user_can_modify_their_data(): void
    {
        $data = [
            'name' => 'New Name',
            'last_name' => 'New LastName'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'Profile Updated',
            'data' => [
                'user' => [
                    'id' => 1,
                    'email' => 'example@example.com',
                    'name' => 'New Name',
                    'last_name' => 'New LastName',
                    'role' => 'ADMIN'
                ]
            ], 
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'example@example.com',
            'name' => 'User',
            'last_name' => 'Test',
            'role' => 'ADMIN'
        ]);
    }

    /**
     * Testing that an authenticated user cannot modify their email.
     */
    public function test_that_an_authenticated_user_cannot_modify_their_email(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'email' => 'newemail@example.com',
            'name' => 'New Name',
            'last_name' => 'New LastName'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'Profile Updated',
            'data' => [
                'user' => [
                    'id' => 1,
                    'email' => 'example@example.com',
                    'name' => 'New Name',
                    'last_name' => 'New LastName',
                    'role' => 'ADMIN'
                ]
            ], 
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'example@example.com',
            'name' => 'New Name',
            'last_name' => 'New LastName',
            'role' => 'ADMIN'
        ]);
    }

    /**
     * Testing that an authenticated user cannot modify their password.
     */
    public function test_that_an_authenticated_user_cannot_modify_their_password(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'password' => 'newPassword',
            'name' => 'New Name',
            'last_name' => 'New LastName'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'Profile Updated',
            'data' => [
                'user' => [
                    'id' => 1,
                    'email' => 'example@example.com',
                    'name' => 'New Name',
                    'last_name' => 'New LastName',
                    'role' => 'ADMIN'
                ]
            ], 
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);
        $user = User::find(1);
        $this->assertFalse(Hash::check('newPassword',$user->password));
    }

    /**
     * Testing that update input name must be required
     */
    public function test_that_update_input_name_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'name' => '',
            'last_name' => 'Hernandez'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field is required.']]]);
    }

    /**
     * Testing that update input name must have at least 2 characters
     */
    public function test_that_update_input_name_must_have_at_least_2_characters(): void
    {
        $data = [
            'name' => 'J',
            'last_name' => 'Hernandez'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be at least 2 characters.']]]);
    }


    /**
     * Testing that update input last name must be required
     */
    public function test_that_update_input_last_name_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'name' => 'Juan',
            'last_name' => ''
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        //$response->dd();
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field is required.']]]);
    }

    /**
     * Testing that update input last name must have at least 2 characters
     */
    public function test_that_update_input_last_name_must_have_at_least_2_characters(): void
    {
        $data = [
            'name' => 'Juan',
            'last_name' => 'H'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be at least 2 characters.']]]);
    }
}
