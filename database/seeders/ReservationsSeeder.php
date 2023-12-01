<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;

class ReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Reservation::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            Reservation::create([
                'name' => $faker->name,
                'table_type' => $faker->randomElement(['type1', 'type2', 'type3']),
                'people' => $faker->randomDigit,
                'time' => $faker->time,
                'date' => $faker->date,
                'phone_number' => $faker->phoneNumber,
                'user_id' => User::inRandomOrder()->first()->id, 
            ]);
        }
    }
}
