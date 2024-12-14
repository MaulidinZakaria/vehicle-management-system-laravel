<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuelConsumption extends EditRecord
{
    protected static string $resource = FuelConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
