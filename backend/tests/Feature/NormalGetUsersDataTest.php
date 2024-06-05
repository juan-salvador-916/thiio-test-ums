<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NormalGetUsersDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated normal user can get the users list
     */
    public function test_that_an_authenticated_normal_user_can_get_the_users_list(): void
    {
        User::factory()->create([
            'name' => 'Normal User',
            'last_name' => 'Normal Test',
            'email' => 'normal_example@example.com',
            'role' => 'NORMAL'
        ]);

        $user_normal = User::where('email', 'normal_example@example.com')->first();
        $this->assertTrue($user_normal->role === 'NORMAL');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $response = $this->apiAs($user_normal, 'get', "{$this->apiBase}/users");
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data' => ['users'], 'status', 'errors']);
    }


    /**
     * Testing that an authenticated normal user cannot get an existing users data
     */
    public function test_that_an_authenticated_normal_user_cannot_get_an_existing_users_data(): void
    {
        User::factory()->create([
            'name' => 'Normal User',
            'last_name' => 'Normal Test',
            'email' => 'normal_example@example.com',
            'role' => 'NORMAL'
        ]);

        $user_normal = User::where('email', 'normal_example@example.com')->first();
        $this->assertTrue($user_normal->role === 'NORMAL');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $user_search_id = User::where('email','juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_normal, 'get', "{$this->apiBase}/users/{$user_search_id}");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }

    /**
     * Testing that an authenticated normal user cannot get an existing users data
     */
    public function test_that_an_un_authenticated_normal_user_cannot_get_users_list(): void
    {
        User::factory()->create([
            'name' => 'Normal User',
            'last_name' => 'Normal Test',
            'email' => 'normal_example@example.com',
            'role' => 'NORMAL'
        ]);

        $user_normal = User::where('email', 'normal_example@example.com')->first();
        $this->assertTrue($user_normal->role === 'NORMAL');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $response = $this->getJson("{$this->apiBase}/users");
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }
}
