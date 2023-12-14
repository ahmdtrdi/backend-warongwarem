<?php
/*
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Table;

use Faker\Factory as Faker;

class UsersReservationsSeeder extends Seeder
{
    public function run()
    {
        // Truncate the tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Reservation::truncate();
        Table::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();

        // Define the roles
        $roles = ['customer', 'waiter', 'manager'];

        // Create the tables
        for ($i = 1; $i <= 15; $i++) {
            $type = '';
            $capacity = 4;
            if ($i <= 6) {
                $type = 'indoor';
            } elseif ($i <= 12) {
                $type = 'outdoor';
            } else {
                $type = 'vip';
                $capacity = 8;
            }

            DB::table('table_list')->insert([
                'table_id' => $i,
                'type' => $type,
                'capacity' => $capacity,
                'on_used' => false,
            ]);
        }

        // Create the users
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'user_id' => $faker->unique()->randomNumber(5),
                'password' => Hash::make('password'),
                'email' => $faker->unique()->safeEmail,
                'role' => $faker->randomElement($roles),
            ]);

            $table = DB::table('table_list')->inRandomOrder()->first();

            // Create a reservation for the user
            Reservation::create([
                'name' => $faker->name,
                'table_type' => $faker->randomElement(['type1', 'type2', 'type3']),
                'people' => $faker->randomDigit,
                'time' => $faker->time,
                'date' => $faker->date,
                'phone_number' => $faker->phoneNumber,
                'user_id' => $user->id,
                'table_id' => $table->table_id,
            ]);
        }
    }
}