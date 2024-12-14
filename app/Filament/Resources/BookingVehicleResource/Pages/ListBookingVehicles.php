<?php

namespace App\Filament\Resources\BookingVehicleResource\Pages;

use App\Filament\Resources\BookingVehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingVehicles extends ListRecords
{
    protected static string $resource = BookingVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
