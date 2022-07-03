<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $user = User::factory()->create();
        return [
            'nama_user' => $user->name,
            'user_id' => $user->id,
            'tanggal' => $this->faker->date(),
            'deskripsi' => $this->faker->words(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'status' => 'Waiting',
        ];
    }
}
