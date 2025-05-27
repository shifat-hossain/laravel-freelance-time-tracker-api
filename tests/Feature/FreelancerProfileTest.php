<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FreelancerProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_authorized_freelancer_profile_data_can_be_retrieved(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['profile']
        );

        $response = $this->get('/api/profile');
        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);
    }

    public function test_unauthorized_freelancer_profile_data_cannot_be_retrieved(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/profile');
        
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_freelancer_profile_data_can_be_updated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['profile']);

        $response = $this->post('/api/profile/update', [
            'name' => 'Updated Name',
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }
}
