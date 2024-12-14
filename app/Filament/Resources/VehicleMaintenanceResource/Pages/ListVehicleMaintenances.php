<?php

namespace App\Filament\Resources\VehicleMaintenanceResource\Pages;

use App\Filament\Resources\VehicleMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleMaintenances extends ListRecords
{
    protected static string $resource = VehicleMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
