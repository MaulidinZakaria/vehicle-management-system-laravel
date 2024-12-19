<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelConsumptionResource\Pages;
use App\Filament\Resources\FuelConsumptionResource\RelationManagers;
use App\Models\FuelConsumption;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FuelConsumptionResource extends Resource
{
    protected static ?string $model = FuelConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Konsumsi BBM';
    protected static ?string $slug = 'konsumsi-bbm';
    protected static ?string $label = 'Konsumsi BBM';
    protected static ?int $navigationSort = 4;

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
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('fuel_volume')
                    ->label('Volume BBM')
                    ->suffix('Liter')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Masukkan Volume BBM'),
                TextInput::make('distance')
                    ->label('Jarak Tempuh')
                    ->suffix('KM')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->placeholder('Masukkan Jarak Tempuh'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('vehicle.model')
                    ->label('Kendaraan')
                    ->description(fn($record) => $record->vehicle ? $record->vehicle->license_plate : '-'),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y')),
                TextColumn::make('fuel_volume')
                    ->label('Volume BBM')
                    ->getStateUsing(fn($record) => $record->fuel_volume . ' Liter'),
                TextColumn::make('distance')
                    ->label('Jarak Tempuh')
                    ->getStateUsing(fn($record) => $record->distance . ' KM'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListFuelConsumptions::route('/'),
        ];
    }
}
