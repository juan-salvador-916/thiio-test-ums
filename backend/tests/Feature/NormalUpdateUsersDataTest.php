<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NormalUpdateUsersDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated normal user cannot update a user
     */
    public function test_that_an_authenticated_normal_user_cannot_update_a_user(): void
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
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $data = [
            'name' => 'New Juan Salvador',
        ];

        $response = $this->apiAs($user_normal, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(403);
        $this->assertDatabaseCount('users', 3);
        $user_not_updated = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($user_not_updated->name === 'Juan Salvador');
    }

    /**
     * Testing that an un-authenticated normal user cannot update users.
     */
    public function test_that_an_un_authenticated_normal_user_cannot_update_users(): void
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
            'name' => 'Juan Salvador',
            'last_name' => 'Hernandez',
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $data = [
            'name' => 'Juan Salvador'
        ];

        $response = $this->putJson("{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(403);
    }
}
