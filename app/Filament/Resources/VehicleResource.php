<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Kendaraan';
    protected static ?string $slug = 'kendaraan';
    protected static ?string $label = 'Kendaraan';
    protected static ?int $navigationSort = 3;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('owner_type')
                    ->label('Pemilik')
                    ->options([
                        'company' => 'Perusahaan',
                        'rental' => 'Rental',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'company') {
                            $set('rental_company_id', null);
                        }
                    }),

                Select::make('rental_company_id')
                    ->label('Perusahaan Rental')
                    ->relationship('rentalCompany', 'name')
                    ->required(fn(string $context, $get): bool => $context === 'create' && $get('owner_type') === 'rental')
                    ->visible(fn($get) => true)
                    ->disabled(fn($get) => $get('owner_type') !== 'rental')
                    ->default(fn($get) => $get('owner_type') === 'company' ? null : $get('rental_company_id'))
                    ->helperText('Pilih Perusahaan Rental hanya jika pemilik adalah rental.'),
                Select::make('vehicle_type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'passenger' => 'Orang',
                        'cargo' => 'Barang',
                    ])
                    ->required(),
                TextInput::make('license_plate')
                    ->label('Plat Nomor')
                    ->placeholder('Masukkan Plat Nomor')
                    ->required(),
                TextInput::make('brand')
                    ->label('Merk')
                    ->placeholder('Masukkan Merk')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(now()->year)
                    ->default(now()->year)
                    ->placeholder('Masukkan tahun'),
                TextInput::make('model')
                    ->label('Model')
                    ->placeholder('Masukkan Model')
                    ->columnSpan(2)
                    ->required(),
                Select::make('fuel_type')
                    ->label('Jenis Bahan Bakar')
                    ->options([
                        'gasoline' => 'Bensin',
                        'diesel' => 'Solar',
                        'electric' => 'Listrik',
                        'hybrid' => 'Hibrida',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'maintenance' => 'Perbaikan',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('owner_type')
                    ->searchable()
                    ->label('Pemilik'),
                TextColumn::make('vehicle_type')
                    ->searchable()
                    ->label('Jenis Kendaraan'),
                TextColumn::make('brand')
                    ->label('Merk')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable()
                    ->label('Model'),
                TextColumn::make('year')
                    ->searchable()
                    ->label('Tahun'),
                TextColumn::make('status')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'available' => 'success',
                        'maintenance' => 'danger',
                        default => 'info',
                    }),
            ])
            ->filters([
                SelectFilter::make('owner_type')
                    ->label('Pemilik')
                    ->options([
                        'company' => 'Perusahaan',
                        'rental' => 'Rental',
                    ]),
                SelectFilter::make('vehicle_type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'passenger' => 'Orang',
                        'cargo' => 'Barang',
                    ]),
                SelectFilter::make('fuel_type')
                    ->label('Jenis Bahan Bakar')
                    ->options([
                        'gasoline' => 'Bensin',
                        'diesel' => 'Solar',
                        'electric' => 'Listrik',
                        'hybrid' => 'Hibrida',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'maintenance' => 'Perbaikan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVehicles::route('/'),
        ];
    }
}
