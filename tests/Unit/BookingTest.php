<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_booking_belongs_to_user()
    {
        $roleUser = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $roleDokter = Role::factory()->create(['name' => 'doctor', 'display_name' => 'Doctor', 'description' => 'Doctor']);

        $userUser = User::factory()->create();
        $userUser->roles()->attach($roleUser);

        $userDokter = User::factory()->create();
        $userDokter->roles()->attach($roleDokter);

        $booking = Booking::factory()->create([
            'nama_user' =>  $userUser->name,
            'user_id' =>  $userUser->id,
            'status' => 'Waiting',
            'user_dokter_id' => $userDokter->id,
            'nama_dokter' => $userDokter->name]);

        $this->assertInstanceOf(User::class, $booking->user);
    }
}
