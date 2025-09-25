<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Faker\Factory as Faker;
use DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        /**
         * Dibawah ini saya petakan position nya , untuk bisa otomatis diloop.
         */
        $positions = ['Manager', 'Staff', 'Developer', 'Fullstack Developer', 'Designer'];

        /**
         * Genarate data employee secara otomatis
         */
        foreach (range(1, 900) as $index) {
            DB::table('employees')->insert([
                'name'       => $faker->name,
                'email'      => $faker->unique()->safeEmail,
                'position'   => $positions[array_rand($positions)],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
