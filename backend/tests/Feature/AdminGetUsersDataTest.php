<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminGetUsersDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that an authenticated admin user can get the users list
     */
    public function test_that_an_authenticated_admin_user_can_get_the_users_list(): void
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

        $response = $this->apiAs($user_admin, 'get', "{$this->apiBase}/users");
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data' => ['users'], 'status', 'errors']);
        $response->assertJsonFragment([
            'data' => [
                'users' => [
                    [
                        'id' => 1,
                        'name' => 'User',
                        'last_name' => 'Test',
                        'email' => 'example@example.com',
                        'role' => 'ADMIN'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Juan Salvador',
                        'last_name' => 'Hernandez',
                        'email' => 'juan@gmail.com',
                        'role' => 'NORMAL'
                    ],
                ]
            ],
            'status' => config('http_constants.ok')
        ]);
    }

    /**
     * Testing that an un-authenticated admin user cannot get users data.
     */
    public function test_that_an_un_authenticated_admin_user_cannot_get_users_data(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);


        $response = $this->getJson("{$this->apiBase}/users");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }

    /**
     * Testing that an authenticated admin user can get data of an existing user
     */
    public function test_that_an_authenticated_admin_user_can_get_data_of_an_existing_user(): void
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


        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'get', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data' => ['user'], 'status', 'errors']);
        $response->assertJsonFragment(['data' => [
            'user' => [
                'id' => $user_search_id,
                'name' => 'Juan Salvador',
                'last_name' => 'Hernandez',
                'email' => 'juan@gmail.com',
                'role' => 'NORMAL'
            ]
        ]]);
    }

    /**
     * Testing that an authenticated admin user cannot get data of an non existing user
     */
    public function test_that_an_authenticated_admin_user_cannot_get_data_of_an_non_existing_user(): void
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


        $user_search_id = '10';

        $response = $this->apiAs($user_admin, 'get', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.not_found'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User not found',
            'status' => config('http_constants.not_found'),
            'errors' => ['User not found']
        ]);
    }

    /**
     * Testing that id param must be valid
     */
    public function test_that_id_param_must_be_valid(): void
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


        $user_search_id = 'asdasd';

        $response = $this->apiAs($user_admin, 'get', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['id']]);
        $response->assertJsonFragment(['errors' => [
            'id' => ['The id field must be an integer.']
        ]]);
    }
}
