<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Carer;

class CarerApiTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_carer()
    {
        $payload = [
            'name' => 'Test Carer',
            'email' => 'testcarer@example.com',
            'phone' => '1234567890',
            'qualifications' => 'Registered Nurse'
        ];

        $response = $this->postJson('/api/carers', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Test Carer',
                     'email' => 'testcarer@example.com',
                     'phone' => '1234567890',
                     'qualifications' => 'Registered Nurse'
                 ]);

        $this->assertDatabaseHas('carers', [
            'email' => 'testcarer@example.com',
            'name' => 'Test Carer',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_carer_list()
    {
        $carers = Carer::factory()->count(2)->create();
        $response = $this->getJson('/api/carers');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $carers[0]->name])
                 ->assertJsonFragment(['name' => $carers[1]->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_a_single_carer()
    {
        $carer = Carer::factory()->create();
        $response = $this->getJson('/api/carers/' . $carer->id);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $carer->name]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_carer()
    {
        $carer = Carer::factory()->create();
        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
            'qualifications' => 'Updated Qualification'
        ];
        $response = $this->putJson('/api/carers/' . $carer->id, $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name', 'email' => 'updated@example.com']);
        $this->assertDatabaseHas('carers', ['id' => $carer->id, 'name' => 'Updated Name']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_a_carer()
    {
        $carer = Carer::factory()->create();
        $response = $this->deleteJson('/api/carers/' . $carer->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('carers', ['id' => $carer->id]);
    }
} 