<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleMaintenanceSeeder extends Seeder
{
    public function run()
    {
        // Assuming the vehicle IDs are from 1 to 15 (adjust if needed)
        $vehicleIds = range(1, 15);

        $maintenances = [];

        // Create maintenance data for each vehicle
        foreach ($vehicleIds as $vehicleId) {
            $maintenances[] = [
                'vehicle_id' => $vehicleId,
                'maintenance_date' => Carbon::now()->subMonths(rand(1, 12)), // Random date in the last 12 months
                'description' => $this->generateMaintenanceDescription(),
                'cost' => rand(50000, 10000000) + rand(0, 99) / 100, // Random cost between 100 and 1000
                'place' => $this->generateMaintenancePlace(),
                'mileage' => rand(10000, 100000) + rand(0, 99) / 100, // Random mileage between 10,000 and 100,000 km
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the data into the vehicle_maintenances table
        DB::table('vehicle_maintenances')->insert($maintenances);
    }

    // Helper function to generate random maintenance descriptions
    private function generateMaintenanceDescription()
    {
        $descriptions = [
            'Oil change and filter replacement',
            'Tire replacement and alignment',
            'Brake pad replacement',
            'Engine tuning and diagnostics',
            'Transmission fluid change',
            'Battery replacement',
            'Suspension repair',
            'Air conditioning maintenance',
            'Fuel system cleaning',
            'Complete vehicle inspection',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    // Helper function to generate random maintenance places
    private function generateMaintenancePlace()
    {
        $places = [
            'Service Center A',
            'Service Center B',
            'Auto Repair Shop X',
            'Authorized Dealer',
            'Local Mechanic',
            'Garage Z',
            'Car Workshop W',
            'Vehicle Maintenance Hub',
        ];

        return $places[array_rand($places)];
    }
}
