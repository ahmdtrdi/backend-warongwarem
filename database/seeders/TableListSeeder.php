<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TableListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('table_list')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
    }
}
