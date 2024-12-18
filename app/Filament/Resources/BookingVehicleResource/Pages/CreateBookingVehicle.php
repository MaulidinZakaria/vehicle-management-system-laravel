<?php

namespace App\Filament\Resources\BookingVehicleResource\Pages;

use App\Filament\Resources\BookingVehicleResource;
use App\Models\Approval;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateBookingVehicle extends CreateRecord
{
    protected static string $resource = BookingVehicleResource::class;
    protected function getFormActions(): array
    {
        $actions = [];

        if ($this->isFormFilled()) {
            $actions[] = $this->getCreateFormAction()->label(
                __('Buat Pemesanan')
            );
        }

        $actions[] = $this->getCancelFormAction();

        return $actions;
    }


    protected function afterFill(): void
    {
        $this->getFormActions();
    }

    protected function isFormFilled(): bool
    {
        return !is_null($this->data['vehicle_id']);
    }

    protected function afterCreate(): void
    {
        $formData = $this->form->getState();

        Approval::create([
            'booking_id' => $this->record->id,
            'approver_id' => $formData['approver_id_1'],
            'approver_level' => 1,
            'status' => 'pending',
        ]);

        Approval::create([
            'booking_id' => $this->record->id,
            'approver_id' => $formData['approver_id_2'],
            'approver_level' => 2,
            'status' => 'pending',
        ]);
        
    }
}
