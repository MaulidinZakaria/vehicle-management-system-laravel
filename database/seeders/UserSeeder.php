<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Admin',
                'level' => 'admin',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor User',
                'email' => 'supervisor1@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Supervisor',
                'level' => 'approver_level_1',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Team Leader User',
                'email' => 'teamleader@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Team Leader',
                'level' => 'approver_level_1',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Manager',
                'level' => 'approver_level_2',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Direktur User',
                'email' => 'direktur@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Direktur',
                'level' => 'approver_level_2',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
