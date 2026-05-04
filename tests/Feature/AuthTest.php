<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'lastname' => 'Player',
            'email' => 'testplayer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertCookie('token');
        $this->assertDatabaseHas('users', [
            'email' => 'testplayer@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        // Must insert firstname since users table expects it
        $user = User::factory()->create([
            'firstname' => 'Login',
            'lastname' => 'Player',
            'email' => 'loginplayer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'loginplayer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertCookie('token');
    }
}
