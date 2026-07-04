<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_user_can_login_with_email(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->post('/dang-nhap', [
            'login' => 'user@example.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_locked_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'locked@example.com',
            'status' => UserStatus::LOCKED,
        ]);

        $response = $this->from('/dang-nhap')->post('/dang-nhap', [
            'login' => 'locked@example.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/dang-nhap');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
