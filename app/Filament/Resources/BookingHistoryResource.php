<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingHistoryResource\Pages;
use App\Filament\Resources\BookingHistoryResource\RelationManagers;
use App\Models\BookingHistory;
use App\Models\BookingVehicle;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BookingHistoryResource extends Resource
{
    protected static ?string $model = BookingVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Riwayat Pemesanan';
    protected static ?string $slug = 'riwayat-pemesanan';
    protected static ?string $label = 'Riwayat Pemesanan';

    public static function canViewAny(): bool
    {
        return Auth::user()?->level === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('request.name')
                    ->label('Pegawai'),
                TextColumn::make('driver.name')
                    ->label('Pengemudi'),
                TextColumn::make('vehicle.model')
                    ->label('Kendaraan'),
                TextColumn::make('start_date')
                    ->label('Tanggal Pemakaian')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->start_date)->translatedFormat('d F Y')),
                TextColumn::make('end_date')
                    ->label('Tanggal Pengembalian')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->end_date)->translatedFormat('d F Y')),
                TextColumn::make('status')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'complete' => 'success',
                        'approved' => 'info',
                        default => 'info',
                    }),
            ])->modifyQueryUsing(function (Builder $query) {
                return $query->whereIn('status', ['approved', 'complete']);
            })
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Disetujui',
                        'complete' => 'Selesai',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Tanggal Awal'),
                        DatePicker::make('created_until')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                ExportAction::make()->label('Export')->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('id')->heading('Booking ID'),
                        Column::make('created_at')->heading('Tanggal Pemesanan'),
                        Column::make('vehicle.model')->heading('Model Kendaraan'),
                        Column::make('vehicle.vehicle_type')->heading('Jenis Kendaraan'),
                        Column::make('vehicle.license_plate')->heading('Nomor Polisi'),
                        Column::make('vehicle.owner_type')->heading('Pemilik Kendaraan'),
                        Column::make('request.name')->heading('Nama Pemesan'),
                        Column::make('request.office_type')->heading('Asal Perusahaan'),
                        Column::make('status')->heading('Status Pemesanan'),
                        Column::make('driver.name')->heading('Pengemudi'),
                        Column::make('start_date')->heading('Tanggal Penggunaan'),
                        Column::make('end_date')->heading('Tanggal Pengembalian'),
                        Column::make('purpose')->heading('Tujuan')
                    ]),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->label('Export'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBookingHistories::route('/'),
        ];
    }
}
