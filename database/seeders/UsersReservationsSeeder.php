<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Reservation;

use Faker\Factory as Faker;

class UsersReservationsSeeder extends Seeder
{
    public function run()
    {
        // Truncate the tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Reservation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();

        // Define the roles
        $roles = ['customer', 'waiter', 'manager'];

        // Create the users
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'password' => Hash::make('password'),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role' => $faker->randomElement($roles),
            ]);

            // Create a reservation for the user
            Reservation::create([
                'name' => $faker->name,
                'table_type' => $faker->randomElement(['type1', 'type2', 'type3']),
                'people' => $faker->randomDigit,
                'time' => $faker->time,
                'date' => $faker->date,
                'phone_number' => $faker->phoneNumber,
                'user_id' => $user->id, 
            ]);
        }
    }
}
