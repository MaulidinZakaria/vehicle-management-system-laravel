<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FuelConsumptionSeeder extends Seeder
{
    public function run()
    {
        // Insert fuel consumption data for 15 vehicles
        $vehicleIds = range(1, 15); // Assuming vehicle IDs from 1 to 15

        $fuelConsumptions = [];

        foreach ($vehicleIds as $vehicleId) {
            $fuelConsumptions[] = [
                'vehicle_id' => $vehicleId,
                'date' => Carbon::now()->subDays(rand(1, 30)), // Random date within the last 30 days
                'fuel_volume' => rand(20, 80) + rand(0, 99) / 100, // Random fuel volume between 20 and 80 liters
                'distance' => rand(100, 500), // Random distance between 100 and 500 km
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the data into the fuel_consumptions table
        DB::table('fuel_consumptions')->insert($fuelConsumptions);
    }
}
