<?php

namespace App\Filament\Resources\BookingHistoryResource\Pages;

use App\Filament\Resources\BookingHistoryResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBookingHistories extends ManageRecords
{
    protected static string $resource = BookingHistoryResource::class;
}
