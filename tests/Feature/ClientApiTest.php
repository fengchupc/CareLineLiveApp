<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_client()
    {
        $payload = [
            'name' => 'Test Client',
            'email' => 'testclient@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'care_needs' => 'Daily care'
        ];
        $response = $this->postJson('/api/clients', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Test Client',
                     'email' => 'testclient@example.com',
                     'phone' => '1234567890',
                     'address' => '123 Main St',
                     'care_needs' => 'Daily care'
                 ]);
        $this->assertDatabaseHas('clients', [
            'email' => 'testclient@example.com',
            'name' => 'Test Client',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_client_list()
    {
        $clients = Client::factory()->count(2)->create();
        $response = $this->getJson('/api/clients');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $clients[0]->name])
                 ->assertJsonFragment(['name' => $clients[1]->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_a_single_client()
    {
        $client = Client::factory()->create();
        $response = $this->getJson('/api/clients/' . $client->id);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $client->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_client()
    {
        $client = Client::factory()->create();
        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
            'address' => '456 New St',
            'care_needs' => 'Updated care'
        ];
        $response = $this->putJson('/api/clients/' . $client->id, $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name', 'email' => 'updated@example.com']);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Name']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_a_client()
    {
        $client = Client::factory()->create();
        $response = $this->deleteJson('/api/clients/' . $client->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
} 