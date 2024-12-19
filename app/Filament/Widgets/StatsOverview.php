<?php

namespace App\Filament\Widgets;

use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $employees = \App\Models\Employee::count();
        $vehicles = \App\Models\Vehicle::count();
        $bookings = \App\Models\BookingVehicle::count();

        return [
            Stat::make('Pegawai', $employees)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('Total Pegawai di Perusahaan')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->color('info'),
            Stat::make('Kendaraan', $vehicles)
                ->description('Total Kendaraan yang Tersedia')
                ->descriptionIcon('heroicon-o-truck', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Stat::make('Pemesanan', $bookings)
                ->description('Total Pemesanan Kendaraan')
                ->descriptionIcon('heroicon-o-document-text', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
