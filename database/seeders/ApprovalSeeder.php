<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $approvals = [
            // Booking ID 1
            ['booking_id' => 1, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 1, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 2
            ['booking_id' => 2, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 2, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 3
            ['booking_id' => 3, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 3, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 4
            ['booking_id' => 4, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 4, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 5
            ['booking_id' => 5, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 5, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 6
            ['booking_id' => 6, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 6, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 7
            ['booking_id' => 7, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 7, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 8
            ['booking_id' => 8, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 8, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 9
            ['booking_id' => 9, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 9, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 10
            ['booking_id' => 10, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 10, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 11
            ['booking_id' => 11, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 11, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 12
            ['booking_id' => 12, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 12, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 13
            ['booking_id' => 13, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'rejected', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 13, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'rejected', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 14
            ['booking_id' => 14, 'approver_id' => 3, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 14, 'approver_id' => 5, 'approver_level' => 2, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],

            // Booking ID 15
            ['booking_id' => 15, 'approver_id' => 2, 'approver_level' => 1, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['booking_id' => 15, 'approver_id' => 4, 'approver_level' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('approvals')->insert($approvals);
    }
}
