<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelConsumptionResource\Pages;
use App\Filament\Resources\FuelConsumptionResource\RelationManagers;
use App\Models\FuelConsumption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
            'index' => Pages\ListFuelConsumptions::route('/'),
            'create' => Pages\CreateFuelConsumption::route('/create'),
            'edit' => Pages\EditFuelConsumption::route('/{record}/edit'),
        ];
    }
}
