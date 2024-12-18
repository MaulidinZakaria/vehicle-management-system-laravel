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
            // Pusat
            [
                'name' => 'Admin User',
                'username' => 'admin',
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
                'username' => 'supervisor',
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
                'username' => 'teamleader',
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
                'username' => 'manager',
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
                'username' => 'direktur',
                'email' => 'direktur@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Direktur',
                'level' => 'approver_level_2',
                'office_type' => 'pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Cabang
            [
                'name' => 'Cabang Supervisor',
                'username' => 'cabang_supervisor',
                'email' => 'supervisor_cabang@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Supervisor',
                'level' => 'approver_level_1',
                'office_type' => 'cabang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cabang Team Leader',
                'username' => 'cabang_teamleader',
                'email' => 'teamleader_cabang@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Team Leader',
                'level' => 'approver_level_1',
                'office_type' => 'cabang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cabang Manager',
                'username' => 'cabang_manager',
                'email' => 'manager_cabang@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Manager',
                'level' => 'approver_level_2',
                'office_type' => 'cabang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cabang Direktur',
                'username' => 'cabang_direktur',
                'email' => 'direktur_cabang@example.com',
                'password' => Hash::make('12345'),
                'position' => 'Direktur',
                'level' => 'approver_level_2',
                'office_type' => 'cabang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
