<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Carer;
use App\Models\Client;
use App\Models\Shift;


class ShiftApiTest extends TestCase {
    use RefreshDatabase;

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_prevents_overlapping_shifts_for_same_carer() {
        $carer = Carer::factory()->create();
        $client = Client::factory()->create();
        // 先插入一个 shift
        Shift::create([
            'carer_id' => $carer->id,
            'client_id' => $client->id,
            'start_time' => '2025-06-25 10:00:00',
            'end_time' => '2025-06-25 12:00:00',
            'notes' => 'First shift'
        ]);
        // 尝试插入重叠 shift
        $payload = [
            'carer_id' => $carer->id,
            'client_id' => $client->id,
            'start_time' => '2025-06-25 11:00:00',
            'end_time' => '2025-06-25 13:00:00',
            'notes' => 'Overlapping shift'
        ];
        $response = $this->postJson('/api/shifts', $payload);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['start_time']);
    }

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_shift()
    {
        $carer = Carer::factory()->create();
        $client = Client::factory()->create();
        $payload = [
            'carer_id' => $carer->id,
            'client_id' => $client->id,
            'start_time' => '2025-06-25 14:00:00',
            'end_time' => '2025-06-25 16:00:00',
            'notes' => 'Test shift'
        ];
        $response = $this->postJson('/api/shifts', $payload);
        $response->assertStatus(201);
        $response->assertJsonPath('notes', 'Test shift');
        $response->assertJsonPath('carer_id', $carer->id);
        $response->assertJsonPath('client_id', $client->id);
        $this->assertDatabaseHas('shifts', [
            'carer_id' => $carer->id,
            'client_id' => $client->id,
            'notes' => 'Test shift'
        ]);
    }

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_shift_list()
    {
        $shifts = Shift::factory()->count(2)->create();
        $response = $this->getJson('/api/shifts');
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $shifts[0]->id])
                 ->assertJsonFragment(['id' => $shifts[1]->id]);
    }

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_a_single_shift()
    {
        $shift = Shift::factory()->create();
        $response = $this->getJson('/api/shifts/' . $shift->id);
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $shift->id]);
    }

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_shift()
    {
        $shift = Shift::factory()->create();
        $payload = [
            'carer_id' => $shift->carer_id,
            'client_id' => $shift->client_id,
            'start_time' => '2025-06-25 18:00:00',
            'end_time' => '2025-06-25 20:00:00',
            'notes' => 'Updated shift'
        ];
        $response = $this->putJson('/api/shifts/' . $shift->id, $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['notes' => 'Updated shift']);
        $this->assertDatabaseHas('shifts', ['id' => $shift->id, 'notes' => 'Updated shift']);
    }

    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_a_shift()
    {
        $shift = Shift::factory()->create();
        $response = $this->deleteJson('/api/shifts/' . $shift->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('shifts', ['id' => $shift->id]);
    }
} 