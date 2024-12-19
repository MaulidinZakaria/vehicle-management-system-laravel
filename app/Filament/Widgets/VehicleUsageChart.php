<?php

namespace App\Filament\Widgets;

use App\Models\BookingVehicle;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class VehicleUsageChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pemakaian Kendaraan';

    protected function getData(): array
    {
        // Ambil data booking dengan status approved atau complete
        $bookings = BookingVehicle::query()
            ->whereIn('status', ['approved', 'complete'])
            ->get(['start_date', 'end_date']);
        
        // Tentukan rentang tanggal dalam 2 minggu terakhir
        $start = now()->subWeeks(2)->startOfDay();
        $end = now()->endOfDay();
        
        // Hitung kendaraan yang terpakai setiap harinya
        $dates = collect();
        foreach (range(0, $start->diffInDays($end)) as $i) {
            $currentDate = $start->clone()->addDays($i);

            $count = $bookings->filter(function ($booking) use ($currentDate) {
                return $booking->start_date <= $currentDate && $booking->end_date >= $currentDate;
            })->count();

            $dates->push([
                'date' => $currentDate->format('Y-m-d'),
                'count' => $count,
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penggunaan Kendaraan',
                    'data' => $dates->pluck('count'),
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dates->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
