<?php

namespace App\Filament\Resources\RentalCompanyResource\Pages;

use App\Filament\Resources\RentalCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalCompany extends EditRecord
{
    protected static string $resource = RentalCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
