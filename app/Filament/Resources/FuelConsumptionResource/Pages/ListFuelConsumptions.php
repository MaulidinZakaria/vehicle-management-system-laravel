<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuelConsumptions extends ListRecords
{
    protected static string $resource = FuelConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
