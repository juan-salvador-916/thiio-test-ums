<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NormalCreateUsersTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated normal user cannot create a user
     */
    public function test_that_an_authenticated_normal_user_cannot_create_a_user(): void
    {

        User::factory()->create([
            'name' => 'Normal User',
            'last_name' => 'Normal Test',
            'email' => 'normal_example@example.com',
            'role' => 'NORMAL'
        ]);

        $user_normal = User::where('email', 'normal_example@example.com')->first();
        $this->assertTrue($user_normal->role === 'NORMAL');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->apiAs($user_normal, 'post', "{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
        $this->assertDatabaseCount('users', 2);
    }

    /**
     * Testing that an un-authenticated admin user cannot create users.
     */
    public function test_that_an_un_authenticated_admin_user_cannot_create_users(): void
    {
        User::factory()->create([
            'name' => 'Normal User',
            'last_name' => 'Normal Test',
            'email' => 'normal_example@example.com',
            'role' => 'NORMAL'
        ]);

        $user_normal = User::where('email', 'normal_example@example.com')->first();
        $this->assertTrue($user_normal->role === 'NORMAL');

        $data = [
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ];

        $response = $this->postJson("{$this->apiBase}/users", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }
}
