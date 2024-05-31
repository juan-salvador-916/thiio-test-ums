<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }


    /**
     * Testing that an authenticated user can update their password.
     */
    public function test_that_an_authenticated_user_can_update_their_password(): void
    {
        $data = [
            'old_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        //$response->dd();
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    /**
     * Testing that old password must be validated
     */
    public function test_that_old_password_must_be_validated(): void
    {
        $data = [
            'old_password' => 'wrongpassword',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        //$response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['old_password']]);
        $response->assertJsonFragment(['errors' => ['old_password' => ['The password does not match.']]]);
    }

    /**
     * Testing that old password must be required
     */
    public function test_that_old_password_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'old_password' => '',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        //$response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['old_password']]);
        $response->assertJsonFragment(['errors' => ['old_password' => ['The old password field is required.']]]);
    }


    /**
     * Testing that password must be required
     */
    public function test_that_password_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'old_password' => 'password',
            'password' => '',
            'password_confirmation' => 'newpassword'
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        //$response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field is required.']]]);
    }

    /**
     * Testing that password must be confirmed
     */
    public function test_that_password_must_be_confirmed(): void
    {
        //$this->withoutExceptionHandling();
        $data = [
            'old_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => ''
        ];
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        //$response->dd();
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment(['errors' => ['password' => ['The password field confirmation does not match.']]]);
    }
}
