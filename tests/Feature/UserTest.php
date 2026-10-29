<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_a_phone_verified_user_and_hides_credentials(): void
    {
        $user = User::factory()->create();

        $this->assertModelExists($user);
        $this->assertNotEmpty($user->phone);
        $this->assertInstanceOf(Carbon::class, $user->phone_verified_at);
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }

    public function test_unverified_factory_state_clears_verification_timestamps(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->phone_verified_at);
    }
}
