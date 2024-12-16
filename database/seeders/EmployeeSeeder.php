<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $roles = ['staff', 'staff & driver'];
        $officeTypes = ['pusat', 'cabang'];

        // Membuat 10 data employee
        for ($i = 0; $i < 10; $i++) {
            DB::table('employees')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'office_type' => $officeTypes[$i % count($officeTypes)],
                'role' => $roles[$i % count($roles)],
                'is_approver' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
