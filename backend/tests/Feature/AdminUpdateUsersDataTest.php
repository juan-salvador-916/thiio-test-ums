<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUpdateUsersDataTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * Testing that an authenticated admin user can update any different user password.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_password(): void
    {
        //$this->withoutExceptionHandling();
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
            'password' => 'newjuan12345',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'Juan Salvador',
                    'last_name' => 'Hernandez',
                    'email' => 'juan@gmail.com',
                    'role' => 'NORMAL'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'Juan Salvador');
        $this->assertTrue($userUpdated -> last_name === 'Hernandez');
        $this->assertTrue($userUpdated -> email === 'juan@gmail.com');
        $this->assertTrue(Hash::check('newjuan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'NORMAL');
    }

    /**
     * Testing that update password must be string.
     */
    public function test_that_update_password_must_be_string(): void
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
            'password' => 12345678
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be a string.']]]);
        
    }

    /**
     * Testing that update password must be at least 8 characters
     */
    public function test_that_update_password_must_be_at_least_8_characters(): void
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
            'password' => 'asd'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field must be at least 8 characters.']]]);
        
    }

    /**
     * Testing that an authenticated admin user can update any different user name.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_name(): void
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
            'name' => 'New Name',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'New Name',
                    'last_name' => 'Hernandez',
                    'email' => 'juan@gmail.com',
                    'role' => 'NORMAL'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'New Name');
        $this->assertTrue($userUpdated -> last_name === 'Hernandez');
        $this->assertTrue($userUpdated -> email === 'juan@gmail.com');
        $this->assertTrue(Hash::check('juan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'NORMAL');
    }

     /**
     * Testing that update name must be string.
     */
    public function test_that_update_name_must_be_string(): void
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
            'name' => 12345678
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be a string.']]]);
        
    }

    /**
     * Testing that update name must be at least 2 characters
     */
    public function test_that_update_name_must_be_at_least_2_characters(): void
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
            'name' => 'a'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment(['errors' => ['name' => ['The name field must be at least 2 characters.']]]);
        
    }

    /**
     * Testing that an authenticated admin user can update any different user last name.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_last_name(): void
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
            'last_name' => 'New LastName',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'Juan Salvador',
                    'last_name' => 'New LastName',
                    'email' => 'juan@gmail.com',
                    'role' => 'NORMAL'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'Juan Salvador');
        $this->assertTrue($userUpdated -> last_name === 'New LastName');
        $this->assertTrue($userUpdated -> email === 'juan@gmail.com');
        $this->assertTrue(Hash::check('juan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'NORMAL');
    }

    /**
     * Testing that update last name must be string.
     */
    public function test_that_update_last_name_must_be_string(): void
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
            'last_name' => 12345678
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be a string.']]]);
        
    }

    /**
     * Testing that update last name must be at least 2 characters
     */
    public function test_that_update_last_name_must_be_at_least_2_characters(): void
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
            'last_name' => 'a'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
        $response->assertJsonFragment(['errors' => ['last_name' => ['The last name field must be at least 2 characters.']]]);
        
    }

    /**
     * Testing that an authenticated admin user can update any different user email.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_email(): void
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
            'email' => 'newjuan@gmail.com',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'Juan Salvador',
                    'last_name' => 'Hernandez',
                    'email' => 'newjuan@gmail.com',
                    'role' => 'NORMAL'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'newjuan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'Juan Salvador');
        $this->assertTrue($userUpdated -> last_name === 'Hernandez');
        $this->assertTrue($userUpdated -> email === 'newjuan@gmail.com');
        $this->assertTrue(Hash::check('juan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'NORMAL');
    }

    /**
     * Testing that update email must be valid.
     */
    public function test_that_update_email_must_be_valid(): void
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
            'email' => 'notemail'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email field must be a valid email address.']]]);
        
    }

    /**
     * Testing that update email must not match to an existing one
     */
    public function test_that_update_email_must_not_match_to_an_existing_one(): void
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

        User::factory()->create([
            'name' => 'Other User',
            'last_name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'test12345',
            'role' => 'NORMAL'
        ]);

        $data = [
            'email' => 'test@test.com'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment(['errors' => ['email' => ['The email has already been taken.']]]);
        
    }

    /**
     * Testing that an authenticated admin user can update any different user role.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_role(): void
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
            'role' => 'ADMIN',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'Juan Salvador',
                    'last_name' => 'Hernandez',
                    'email' => 'juan@gmail.com',
                    'role' => 'ADMIN'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'juan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'Juan Salvador');
        $this->assertTrue($userUpdated -> last_name === 'Hernandez');
        $this->assertTrue($userUpdated -> email === 'juan@gmail.com');
        $this->assertTrue(Hash::check('juan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'ADMIN');
    }

    /**
     * Testing that update role must be valid.
     */
    public function test_that_update_role_must_be_valid(): void
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
            'role' => 'notrole'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.unprocessable_entity'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['role']]);
        $response->assertJsonFragment(['errors' => ['role' => ['The selected role is invalid.']]]);
        
    }

     /**
     * Testing that an authenticated admin user can update any different user data with multiple fields.
     */
    public function test_that_an_authenticated_admin_user_can_update_any_different_user_data_with_multiple_fields(): void
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
            'name' => 'New Juan Salvador',
            'last_name' => 'New Hernandez',
            'email' => 'newjuan@gmail.com',
            'password' => 'newjuan12345',
            'role' => 'ADMIN'
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->apiAs($user_admin, 'put', "{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.ok'));
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment([
            'message' => 'User Updated',
            'data' => [
                'user' => [
                    'id' => 2,
                    'name' => 'New Juan Salvador',
                    'last_name' => 'New Hernandez',
                    'email' => 'newjuan@gmail.com',
                    'role' => 'ADMIN'
                ]
            ],
            'status' => config('http_constants.ok'),
            'errors' => []
        ]);

        $userUpdated = User::where('email', 'newjuan@gmail.com')->first();
        $this->assertTrue($userUpdated -> name === 'New Juan Salvador');
        $this->assertTrue($userUpdated -> last_name === 'New Hernandez');
        $this->assertTrue($userUpdated -> email === 'newjuan@gmail.com');
        $this->assertTrue(Hash::check('newjuan12345', $userUpdated->password));
        $this->assertTrue($userUpdated -> role === 'ADMIN');
    }


    /**
     * Testing that an un-authenticated admin user cannot update any different user password.
     */
    public function test_that_an_un_authenticated_admin_user_cannot_update_any_different_user_password(): void
    {
        $user_admin = User::where('email', 'example@example.com')->first();
        $this->assertTrue($user_admin->role === 'ADMIN');

        User::factory()->create([
            'email' => 'juan@gmail.com',
            'password' => 'juan12345',
            'role' => 'NORMAL'
        ]);

        $data = [
            'password' => 'newjuan12345',
        ];

        $user_search_id = User::where('email', 'juan@gmail.com')->first()->id;

        $response = $this->putJson("{$this->apiBase}/users/{$user_search_id}", $data);
        //$response->dd();

        $response->assertStatus(config('http_constants.forbidden'));
    }


}
