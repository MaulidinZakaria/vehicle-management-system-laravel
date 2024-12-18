<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalResource\Pages;
use App\Filament\Resources\ApprovalResource\RelationManagers;
use App\Models\Approval;
use App\Models\BookingVehicle;
use App\Models\Employee;
use App\Models\User;
use App\Models\Vehicle;
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Livewire;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Persetujuan';
    protected static ?string $slug = 'persetujuan';
    protected static ?string $label = 'Persetujuan';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationBadgeTooltip = 'Jumlah Pemesanan';

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if (!$user) {
            return null; // Pastikan user login
        }

        $count = Approval::where('approver_id', $user->id)
            ->where('status', 'pending')
            ->when($user->level === 'approver_level_2', function ($query) {
                $query->whereHas('booking.approvals', function ($subQuery) {
                    $subQuery->where('approver_level', 1)
                        ->where('status', 'approved');
                });
            })
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('pemesan')
                    ->label('Pemesan')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $request = Employee::find($booking->requested_id);
                            if ($request) {
                                $set('pemesan', $request->name);
                            }
                        }
                    }),
                TextInput::make('driver')
                    ->label('Pengemudi')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $driver = Employee::find($booking->driver_id);
                            if ($driver) {
                                $set('driver', $driver->name);
                            }
                        }
                    }),
                TextInput::make('model')
                    ->label('Model Kendaraan')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $vehicle = Vehicle::find($booking->vehicle_id);
                            if ($vehicle) {
                                $set('model', $vehicle->model);
                            }
                        }
                    }),
                TextInput::make('plat')
                    ->label('Plat Nomer')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $vehicle = Vehicle::find($booking->vehicle_id);
                            if ($vehicle) {
                                $set('plat', $vehicle->license_plate);
                            }
                        }
                    }),
                Select::make('vehicle_type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'cargo' => 'Barang',
                        'passenger' => 'Penumpang',
                    ])
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $vehicle = Vehicle::find($booking->vehicle_id);
                            if ($vehicle) {
                                $set('vehicle_type', $vehicle->vehicle_type);
                            }
                        }
                    }),
                Select::make('owner_type')
                    ->label('Pemilik Kendaraan')
                    ->options([
                        'company' => 'Perusahaan',
                        'rental' => 'Rental',
                    ])
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            $vehicle = Vehicle::find($booking->vehicle_id);
                            if ($vehicle) {
                                $set('owner_type', $vehicle->owner_type);
                            }
                        }
                    }),
                DatePicker::make('start_date')
                    ->label('Tanggal pakai')
                    ->displayFormat('d F Y')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            if ($booking) {
                                $set('start_date', $booking->start_date);
                            }
                        }
                    }),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->displayFormat('d F Y')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            if ($booking) {
                                $set('end_date', $booking->end_date);
                            }
                        }
                    }),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                TextInput::make('approver_name')
                    ->label('Penyetuju')
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $approverId = $get('approver_id');
                        if ($approverId) {
                            $approver = User::find($approverId);
                            if ($approver) {
                                $set('approver_name', $approver->name);
                            }
                        }
                    }),
                Textarea::make('purpose')
                    ->label('Tujuan')
                    ->rows(3)
                    ->columnSpanFull()
                    ->afterStateHydrated(function ($state, $set, $get) {
                        $bookingId = $get('booking_id');
                        if ($bookingId) {
                            $booking = BookingVehicle::find($bookingId);
                            if ($booking) {
                                $set('purpose', $booking->purpose);
                            }
                        }
                    }),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', (Auth::user()?->level == 'admin' ? 'desc' : 'asc'))
            ->columns([
                TextColumn::make('booking.request.name')
                    ->searchable()
                    ->label('Pemesan'),
                TextColumn::make('booking.vehicle.model')
                    ->searchable()
                    ->description(fn($record): string => $record->booking->vehicle->license_plate)
                    ->label('Model Kendaraan'),
                TextColumn::make('booking.start_date')
                    ->formatStateUsing(fn($state): string => \Carbon\Carbon::parse($state)->translatedFormat('d F Y'))
                    ->description(fn($record): string => \Carbon\Carbon::parse($record->booking->end_date)->translatedFormat('d F Y'))
                    ->label('Tanggal Pakai'),
                TextColumn::make('approver.name')
                    ->searchable()
                    ->hidden(fn() => Auth::user()?->level !== 'admin')
                    ->label('Penyetuju'),
                TextColumn::make('approver_level')
                    ->badge()
                    ->hidden(fn() => Auth::user()?->level !== 'admin')
                    ->color(fn($state) => match ($state) {
                        1 => 'info',
                        2 => 'success',
                        default => 'info',
                    })
                    ->label('Level'),
                TextColumn::make('booking.purpose')
                    ->limit(40)
                    ->hidden(fn() => Auth::user()?->level === 'admin')
                    ->label('Tujuan'),
                (Auth::user()?->level === 'admin' ?
                    TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'rejected' => 'danger',
                        'approved' => 'success',
                        'pending' => 'info',
                        default => 'info',
                    }) : SelectColumn::make('status')
                    ->label('Status')
                    ->afterStateUpdated(function ($state, $record) {
                        $userLevel = Auth::user()?->level;

                        if (!$record->booking_id) {
                            return;
                        }

                        if ($state === 'approved' || $state === 'rejected') {
                            if ($userLevel === 'approver_level_2' || ($userLevel === 'approver_level_1' && $state === 'rejected')) {

                                BookingVehicle::where('id', $record->booking_id)->update([
                                    'status' => $state,
                                ]);

                                if ($userLevel === 'approver_level_1') {
                                    Approval::where('booking_id', $record->booking_id)->update([
                                        'status' => $state,
                                    ]);
                                }
                            }
                        }

                        return redirect('/persetujuan');
                    })
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                ),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()?->level == 'approver_level_1') {
                    return $query->where('approver_id', Auth::user()?->id)->where('status', 'pending');
                } else if (Auth::user()?->level == 'approver_level_2') {
                    return $query->where('status', 'pending')
                        ->whereHas('booking.approvals', function ($subQuery) {
                            $subQuery->where('approver_level', 1)
                                ->where('status', 'approved');
                        });
                } else {
                    return $query;
                }
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovals::route('/'),
        ];
    }
}
