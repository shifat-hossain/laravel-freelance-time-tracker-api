<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_users_cannot_get_clients_resource_route(): void
    {
        //Client List
        $reposnse = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/clients');
        $this->assertEquals(401, $reposnse->getStatusCode());

        //Client Create
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('api/clients', [
            'name' => 'Test Client',
            'email' => '',
            'contact_person' => 'John Doe',
            'user_id' => 1,
        ]);
        $this->assertEquals(401, $response->getStatusCode());

        //Client Edit
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('api/clients/1/edit');
        $this->assertEquals(401, $response->getStatusCode());

        //Client Update
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->putJson('api/clients/1', [
            'name' => 'Updated Client',
            'email' => '',
            'contact_person' => 'Jane Doe',
            'user_id' => 1,
        ]);
        $this->assertEquals(401, $response->getStatusCode());

        //Client delete
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->deleteJson('api/clients/1');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_freelance_can_get_client_list(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/clients');
        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);
    }

    public function test_freelancer_can_create_client(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/clients', [
            'name' => 'Test Client',
            'email' => 'testclient@example.com',
            'contact_person' => 'John Doe',
            'user_id' => $user->id,
        ]);
        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
        ]);
        $this->assertDatabaseHas('clients', [
            'name' => 'Test Client',
            'email' => 'testclient@example.com',
            'contact_person' => 'John Doe',
            'user_id' => $user->id,
        ]);
    }

    public function test_client_create_validation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/clients', [
            'name' => '',
            'email' => '',
            'contact_person' => 'John Doe',
            'user_id' => $user->id,
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email']);

        //Check Unique Email Validation
        $client = Client::factory()->create();
        $response = $this->postJson('/api/clients', [
            'name' => 'Test Client',
            'email' => $client->email,
            'contact_person' => 'John Doe',
            'user_id' => $user->id,
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
        
        //Check Email Format Validation
        $response = $this->postJson('/api/clients', [
            'name' => 'Test Client',
            'email' => 'invalidEmail',
            'contact_person' => 'John Doe',
            'user_id' => $user->id,
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }
}
