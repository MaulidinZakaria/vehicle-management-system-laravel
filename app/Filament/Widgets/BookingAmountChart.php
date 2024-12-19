<?php

namespace App\Filament\Widgets;

use App\Models\BookingVehicle;
use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class BookingAmountChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pemesanan Kendaraan';
    protected static ?int $sort = 20;

    protected function getData(): array
    {
        $data = Trend::model(BookingVehicle::class)
            ->between(
                start: now()->subWeek(),
                end: now(),
            )
            // ->where('status', 'approved')
            ->perDay()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Pemesanan Kendaraan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
