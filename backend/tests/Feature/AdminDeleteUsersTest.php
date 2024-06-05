<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminDeleteUsersTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated admin user can delete an existing user.
     */
    public function test_that_an_authenticated_admin_user_can_delete_an_existing_user(): void
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

        $response = $this->apiAs($user_admin, 'delete', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User Deleted', 'status' => config('http_constants.ok')]);
        
        $this->assertDatabaseMissing('users',[
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);
        $this->assertDatabaseCount('users',1);
    }

    /**
     * Testing that an authenticated admin user cannot delete a non existing user.
     */
    public function test_that_an_authenticated_admin_user_cannot_delete_a_non_existing_user(): void
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

        $user_search_id = "11";

        $response = $this->apiAs($user_admin, 'delete', "{$this->apiBase}/users/{$user_search_id}");
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
     * Testing that an un-authenticated admin user cannot delete a user.
     */
    public function test_that_an_un_authenticated_admin_user_cannot_delete_a_user(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->deleteJson("{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }

    /**
     * Testing that an authenticated admin user cannot delete his own user record.
     */
    public function test_that_an_authenticated_admin_user_cannot_delete_his_own_user_record(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $user_search_id = User::where('email', 'example@example.com')->first()->id;

        $response = $this->apiAs($user_admin, 'delete', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'You cannot delete your own user',
            'status' => config('http_constants.forbidden'),
            'errors' => ['You cannot delete your own user']
        ]);
        
        $this->assertDatabaseHas('users',[
            'name' => 'User',
            'last_name' => 'Test',
            'email' => 'example@example.com',
            'role' => 'ADMIN'
        ]);
        $this->assertDatabaseCount('users',1);
    }

    /**
     * Testing that an authenticated admin user cannot delete the admin root user.
     */
    public function test_that_an_authenticated_admin_user_cannot_delete_the_admin_root_user(): void
    {

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'ADMIN'
        ]);

        $user_admin = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        $user_search_id = User::where('email', 'example@example.com')->first()->id;

        $response = $this->apiAs($user_admin, 'delete', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'You cannot delete the admin root user',
            'status' => config('http_constants.forbidden'),
            'errors' => ['You cannot delete the admin root user']
        ]);
        
        $this->assertDatabaseHas('users',[
            'name' => 'User',
            'last_name' => 'Test',
            'email' => 'example@example.com',
            'role' => 'ADMIN'
        ]);
        $this->assertDatabaseCount('users',2);
    }
}
