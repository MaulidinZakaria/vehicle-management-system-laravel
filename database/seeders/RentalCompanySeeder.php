<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalCompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('rental_companies')->insert([
            [
                'name' => 'Speedy Rentals',
                'email' => 'contact@speedyrentals.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GoRent Car Hire',
                'email' => 'info@gorent.com',
                'phone' => '085678123456',
                'address' => 'Jl. Raya No. 2, Surabaya, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'City Ride Rentals',
                'email' => 'cityride@rentals.com',
                'phone' => '082312345678',
                'address' => 'Jl. Sudirman No. 3, Bandung, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fast Track Rentals',
                'email' => 'hello@fasttrack.com',
                'phone' => '089234567891',
                'address' => 'Jl. Gatot Subroto No. 4, Yogyakarta, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'EcoDrive Rentals',
                'email' => 'contact@ecodrive.com',
                'phone' => '087654321234',
                'address' => 'Jl. Siliwangi No. 5, Bali, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
