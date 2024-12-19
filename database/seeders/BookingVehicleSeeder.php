<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->format('Y-m-d');

        $data = [
            // 10 Data dengan status approved
            [
                'vehicle_id' => 1,
                'requested_id' => 3,
                'driver_id' => 5,
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-03',
                'purpose' => 'Delivery project to client',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 2,
                'requested_id' => 4,
                'driver_id' => 5,
                'start_date' => '2024-12-05',
                'end_date' => '2024-12-07',
                'purpose' => 'Team outing transportation',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 3,
                'requested_id' => 6,
                'driver_id' => 8,
                'start_date' => '2024-12-08',
                'end_date' => '2024-12-10',
                'purpose' => 'Office supplies delivery',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 4,
                'requested_id' => 7,
                'driver_id' => 9,
                'start_date' => '2024-12-12',
                'end_date' => '2024-12-14',
                'purpose' => 'Client meeting in another city',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 5,
                'requested_id' => 3,
                'driver_id' => 10,
                'start_date' => '2024-11-15',
                'end_date' => '2024-11-17',
                'purpose' => 'Office relocation support',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 6,
                'requested_id' => 4,
                'driver_id' => 11,
                'start_date' => '2024-11-18',
                'end_date' => '2024-11-19',
                'purpose' => 'Employee training transport',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 7,
                'requested_id' => 5,
                'driver_id' => 12,
                'start_date' => '2024-11-20',
                'end_date' => '2024-11-22',
                'purpose' => 'Warehouse inventory check',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 8,
                'requested_id' => 6,
                'driver_id' => 13,
                'start_date' => '2024-11-23',
                'end_date' => '2024-11-25',
                'purpose' => 'Field work for survey',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 9,
                'requested_id' => 7,
                'driver_id' => 14,
                'start_date' => '2024-11-26',
                'end_date' => '2024-11-28',
                'purpose' => 'Inter-branch material transfer',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 10,
                'requested_id' => 8,
                'driver_id' => 15,
                'start_date' => '2024-11-29',
                'end_date' => '2024-11-30',
                'purpose' => 'VIP client pick-up',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 5 Data dengan status bebas
            [
                'vehicle_id' => 1,
                'requested_id' => 3,
                'driver_id' => 5,
                'start_date' => '2024-12-15',
                'end_date' => '2024-12-16',
                'purpose' => 'Conference transport',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 2,
                'requested_id' => 4,
                'driver_id' => 5,
                'start_date' => '2024-12-17',
                'end_date' => '2024-12-19',
                'purpose' => 'Special project support',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 3,
                'requested_id' => 6,
                'driver_id' => 8,
                'start_date' => '2024-12-20',
                'end_date' => '2024-12-21',
                'purpose' => 'Event logistics support',
                'status' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 4,
                'requested_id' => 7,
                'driver_id' => 9,
                'start_date' => '2024-12-22',
                'end_date' => '2024-12-23',
                'purpose' => 'Branch opening support',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 5,
                'requested_id' => 3,
                'driver_id' => 10,
                'start_date' => '2024-12-24',
                'end_date' => '2024-12-25',
                'purpose' => 'Office equipment delivery',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('booking_vehicles')->insert($data);
    }
}
