<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FreelancerRegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_freelancer_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure(
            [
                'success',
                'data',
                'message'
            ]
        );

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_freelancer_registration_requires_name(): void
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_freelancer_registration_name_must_be_string(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 12345, // Invalid name type
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_freelancer_registration_name_value_must_not_exceed_max_length(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_freelancer_registration_requires_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_freelancer_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_freelancer_registration_requires_unique_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_freelancer_registration_requires_password(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_freelancer_registration_password_must_be_minimum_length(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => '123', // Invalid password length
            'password_confirmation' => '123', // Invalid password confirmation length
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_freelancer_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            // 'password_confirmation' => 'password', // Intentionally missing
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_freelancer_registration_requires_password_confirmation_match(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }
}
