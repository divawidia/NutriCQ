<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingTest extends TestCase
{
    public function authUser()
    {
        $user = User::factory()->create();
        $user->attachRole('user');
        Sanctum::actingAs($this->user);
        return $user;
    }

    public function test_get_mybooking()
    {
        $this->authUser();
        $response = $this->get('/api/mybooking');
        $response->assertStatus(200);
    }

    public function test_booking_store()
    {
        $this->authUser();
        $doctor = User::factory()->create();
        // $doctor->attachRole('doctor');
        // ['user_dokter_id' => $doctor->id, 'nama_dokter' => $doctor->name]
        $booking = Booking::factory()->make();
        dd($booking);

        $response = $this->postJson(route('booking.store'), ['user_id' => $booking->user_id])
            ->assertStatus(404)
            ->json();
        $this->assertEquals($booking->user_id, $response['user_id']);
        $this->assertDatabaseHas('bookings', ['user_id' => $booking->user_id]);
    }
}
