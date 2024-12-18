<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleMaintenanceResource\Pages;
use App\Filament\Resources\VehicleMaintenanceResource\RelationManagers;
use App\Models\VehicleMaintenance;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VehicleMaintenanceResource extends Resource
{
    protected static ?string $model = VehicleMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Servis Kendaraan';
    protected static ?string $slug = 'servis-kendaraan';
    protected static ?string $label = 'Servis Kendaraan';
    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return Auth::user()?->level === 'admin';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('vehicle_id')
                    ->label('Kendaraan')
                    ->relationship('vehicle', 'model')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->model . ' - ' . $record->license_plate)
                    ->required(),
                DatePicker::make('maintenance_date')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('cost')
                    ->label('Biaya')
                    ->prefix('Rp')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Masukkan Biaya'),
                TextInput::make('mileage')
                    ->label('Jarak Tempuh')
                    ->suffix('KM')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Masukkan Jarak Tempuh'),
                Textarea::make('place')
                    ->label('Tempat')
                    ->required()
                    ->columnSpan(2)
                    ->placeholder('Masukkan Tempat'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpan(2)
                    ->placeholder('Masukkan Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('vehicle.model')
                    ->label('Kendaraan')
                    ->description(fn($record) => $record->vehicle ? $record->vehicle->license_plate : '-'),
                TextColumn::make('maintenance_date')
                    ->label('Tanggal')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y')),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                TextColumn::make('cost')
                    ->label('Biaya')
                    ->getStateUsing(fn($record) => 'Rp ' . number_format($record->cost, 0, ',', '.')),
                TextColumn::make('place')
                    ->label('Tempat')
                    ->limit(50),
            ])
            ->filters([
                //
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
            'index' => Pages\ListVehicleMaintenances::route('/'),
        ];
    }
}
