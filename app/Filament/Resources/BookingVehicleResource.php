<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingVehicleResource\Pages;
use App\Filament\Resources\BookingVehicleResource\RelationManagers;
use App\Models\Approval;
use App\Models\BookingVehicle;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\CarbonPeriod;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class BookingVehicleResource extends Resource
{
    protected static ?string $model = BookingVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $slug = 'pemesanan';
    protected static ?string $label = 'Pemesanan';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return Auth::user()?->level === 'admin';
    }

    public static function getDisabledDates($vehicleType, $ownerType)
    {
        if (is_null($vehicleType) || is_null($ownerType)) {
            return [];
        }

        $vehiclesCount = Vehicle::where('vehicle_type', $vehicleType)
            ->where('owner_type', $ownerType)
            ->count();

        $bookings = BookingVehicle::whereHas('vehicle', function ($query) use ($vehicleType, $ownerType) {
            $query->where('vehicle_type', $vehicleType)
                ->where('owner_type', $ownerType);
        })->whereIn('status', ['pending', 'approved'])
            ->select('start_date', 'end_date')
            ->get();

        $dateUsage = [];

        foreach ($bookings as $booking) {

            $range = CarbonPeriod::create($booking->start_date, $booking->end_date);

            foreach ($range as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dateUsage[$formattedDate] = ($dateUsage[$formattedDate] ?? 0) + 1;
            }
        }

        $disabledDates = array_keys(array_filter($dateUsage, fn($usage) => $usage >= $vehiclesCount));

        return $disabledDates;
    }

    public static function getNearestFullDate($startDate, $vehicleType, $ownerType)
    {
        if (is_null($vehicleType) || is_null($ownerType)) {
            return null;
        }

        $vehiclesCount = Vehicle::where('vehicle_type', $vehicleType)
            ->where('owner_type', $ownerType)
            ->count();

        $bookings = BookingVehicle::whereHas('vehicle', function ($query) use ($vehicleType, $ownerType) {
            $query->where('vehicle_type', $vehicleType)
                ->where('owner_type', $ownerType);
        })->whereIn('status', ['pending', 'approved'])
            ->select('start_date', 'end_date')
            ->get();

        $dateUsage = [];

        foreach ($bookings as $booking) {
            $range = CarbonPeriod::create($booking->start_date, $booking->end_date);
            foreach ($range as $date) {
                $formattedDate = $date->format('Y-m-d');
                $dateUsage[$formattedDate] = ($dateUsage[$formattedDate] ?? 0) + 1;
            }
        }


        foreach ($dateUsage as $date => $usage) {
            if ($date >= $startDate && $usage >= $vehiclesCount) {
                return $date;
            }
        }

        return null;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Informasi Dasar')
                        ->schema([
                            Select::make('requested_id')
                                ->label('Pegawai')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(1)
                                ->relationship('request', 'name')
                                ->getOptionLabelFromRecordUsing(fn($record) => $record->name . ' - ' . $record->email),

                            Select::make('driver_id')
                                ->label('Pengemudi')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(1)
                                ->relationship(
                                    name: 'driver',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn(Builder $query) => $query->where('role', 'staff & driver')
                                )
                                ->getOptionLabelFromRecordUsing(fn($record) => $record->name . ' - ' . $record->email),

                            Select::make('vehicle_type')
                                ->label('Jenis Kendaraan')
                                ->options([
                                    'cargo' => 'Barang',
                                    'passenger' => 'Penumpang',
                                ])
                                ->reactive()
                                ->afterStateHydrated(callback: function ($state, $set, $get) {
                                    $vehicleId = $get('vehicle_id');
                                    if ($vehicleId) {
                                        $vehicle = Vehicle::find($vehicleId);
                                        if ($vehicle) {
                                            $set('vehicle_type', $vehicle->vehicle_type);
                                        }
                                    }
                                })
                                ->required()
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, $set) {
                                    $set('vehicle_id', null);
                                    $set('start_date', null);
                                    $set('end_date', null);
                                }),

                            Select::make('owner_type')
                                ->label('Pemilik Kendaraan')
                                ->options([
                                    'company' => 'Perusahaan',
                                    'rental' => 'Rental',
                                ])
                                ->reactive()
                                ->required()
                                ->afterStateHydrated(function ($state, $set, $get) {
                                    $vehicleId = $get('vehicle_id');
                                    if ($vehicleId) {
                                        $vehicle = Vehicle::find($vehicleId);
                                        if ($vehicle) {
                                            $set('owner_type', $vehicle->owner_type);
                                        }
                                    }
                                })
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, $set) {
                                    $set('vehicle_id', null);
                                    $set('start_date', null);
                                    $set('end_date', null);
                                }),

                            Textarea::make('purpose')
                                ->label('Tujuan')
                                ->required()
                                ->columnSpan(2)
                                ->placeholder('Masukkan Tujuan'),
                        ])->columns(2),

                    Wizard\Step::make('Pilih Tanggal dan Kendaraan')
                        ->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->native(false)
                                ->minDate(now()->startOfDay())
                                ->placeholder('Pilih Tanggal Mulai')
                                ->disabledDates(fn($get) => static::getDisabledDates($get('vehicle_type'), $get('owner_type')))
                                ->reactive()
                                ->required()
                                ->columnSpan(1)
                                ->displayFormat('d F Y'),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->native(false)
                                ->minDate(fn($get) => $get('start_date'))
                                ->maxDate(function ($get) {
                                    $startDate = $get('start_date');
                                    $vehicleType = $get('vehicle_type');
                                    $ownerType = $get('owner_type');

                                    $nearestFullDate = static::getNearestFullDate($startDate, $vehicleType, $ownerType);

                                    if ($nearestFullDate) {
                                        return date('Y-m-d', strtotime('-1 day', strtotime($nearestFullDate)));
                                    }

                                    return null;
                                })
                                ->placeholder('Pilih Tanggal Selesai')
                                ->disabledDates(fn($get) => static::getDisabledDates($get('vehicle_type'), $get('owner_type')))
                                ->reactive()
                                ->required()
                                ->columnSpan(1)
                                ->displayFormat('d F Y'),

                            Select::make('vehicle_id')
                                ->label('Kendaraan')
                                ->relationship('vehicle', 'model')
                                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->model} - {$record->license_plate}")
                                ->preload()
                                ->required()
                                ->columnSpan(2)
                                ->searchable()
                                ->options(function ($get) {
                                    $vehicleType = $get('vehicle_type');
                                    $ownerType = $get('owner_type');
                                    $startDate = $get('start_date');
                                    $endDate = $get('end_date');

                                    if (!$vehicleType || !$ownerType || !$startDate || !$endDate) {
                                        return [];
                                    }

                                    $vehicles = Vehicle::query()
                                        ->where('vehicle_type', $vehicleType)
                                        ->where('owner_type', $ownerType)
                                        ->where('status', 'available')
                                        ->whereNotExists(function ($query) use ($startDate, $endDate) {
                                            $query->select(DB::raw(1))
                                                ->from('booking_vehicles')
                                                ->whereColumn('booking_vehicles.vehicle_id', 'vehicles.id')
                                                ->whereIn('status', ['pending', 'approved'])
                                                ->where(function ($query) use ($startDate, $endDate) {
                                                    $query->whereBetween('start_date', [$startDate, $endDate])
                                                        ->orWhereBetween('end_date', [$startDate, $endDate])
                                                        ->orWhere(function ($query) use ($startDate, $endDate) {
                                                            $query->where('start_date', '<=', $startDate)
                                                                ->where('end_date', '>=', $endDate);
                                                        });
                                                });
                                        })
                                        ->pluck('model', 'id');
                                    if ($vehicles->isEmpty()) {
                                        return ['' => 'Tidak ada kendaraan yang tersedia sesuai filter'];
                                    }

                                    return $vehicles;
                                })
                                ->reactive()
                                ->placeholder('Pilih kendaraan'),
                        ])->columns(2),
                    Wizard\Step::make('Pihak yang Menyetujui')
                        ->schema([
                            Select::make('approver_id_1')
                                ->label('Penyetuju Level 1')
                                ->searchable()
                                ->required()
                                ->disabled(fn($get) => $get('id') !== null)
                                ->afterStateHydrated(function ($state, $set, $get) {
                                    $bookingId = $get('id');
                                    if ($bookingId) {
                                        $approver = Approval::where('booking_id', $bookingId)->where('approver_level', 1)->first();
                                        if ($approver) {
                                            $set('approver_id_1', $approver->approver->name);
                                        }
                                    }
                                })
                                ->preload()
                                ->columnSpan(1)
                                ->options(fn($get) => User::where('level', 'approver_level_1')
                                    ->when($get('requested_id'), fn($query, $requestedId) => $query->where('id', '!=', $requestedId))
                                    ->pluck('name', 'id')),
                            Select::make('approver_id_2')
                                ->label('Penyetuju Level 2')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled(fn($get) => $get('id') !== null)
                                ->afterStateHydrated(function ($state, $set, $get) {
                                    $bookingId = $get('id');
                                    if ($bookingId) {
                                        $approver = Approval::where('booking_id', $bookingId)->where('approver_level', 2)->first();
                                        if ($approver) {
                                            $set('approver_id_2', $approver->approver->name);
                                        }
                                    }
                                })
                                ->columnSpan(1)
                                ->options(fn($get) => User::where('level', 'approver_level_2')
                                    ->when($get('requested_id'), fn($query, $requestedId) => $query->where('id', '!=', $requestedId))
                                    ->pluck('name', 'id')),
                        ])->columns(2),
                ])->columnSpanFull()
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
                    ->label('Tanggal Mulai')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->start_date)->translatedFormat('d F Y')),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->end_date)->translatedFormat('d F Y')),
                TextColumn::make('status')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'rejected' => 'danger',
                        'approved' => 'success',
                        'pending' => 'info',
                        default => 'info',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn($record) => $record->status === 'pending'),
                Tables\Actions\DeleteAction::make()->visible(fn($record) => $record->status === 'rejected'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingVehicles::route('/'),
            'create' => Pages\CreateBookingVehicle::route('/create'),
        ];
    }
}
