<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Reservation;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_reservation()
    {
        // Create a user for testing purpose
        $user = User::factory()->create();

        // Reservation data to be tested
        $reservationData = [
            "name" => "Nama Pengguna",
            "table_type" => "Indoor",
            "people" => 12,
            "time" => "19:00",
            "date" => "2023-12-31",
            "phone_number" => "1234567890"
        ];

        // Request to the endpoint to create a reservation
        $response = $this->actingAs($user)->postJson('/api/reservations', $reservationData);

        // Ensure that the response is a 201 Created
        $response->assertStatus(201);

        // Ensure that the data has been stored in the 'reservations' table with the correct values
        $this->assertDatabaseHas('reservations', [
            'name' => $reservationData['name'],
            'table_type' => $reservationData['table_type'],
            'people' => $reservationData['people'],
            'time' => $reservationData['time'],
            'date' => $reservationData['date'],
            'phone_number' => $reservationData['phone_number'],
            // Also verify that 'user_id' exists in the table
        ]);
    }
}
