<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleMaintenanceResource\Pages;
use App\Filament\Resources\VehicleMaintenanceResource\RelationManagers;
use App\Models\VehicleMaintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

    public static function shouldRegisterNavigation(): bool
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
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'create' => Pages\CreateVehicleMaintenance::route('/create'),
            'edit' => Pages\EditVehicleMaintenance::route('/{record}/edit'),
        ];
    }
}
