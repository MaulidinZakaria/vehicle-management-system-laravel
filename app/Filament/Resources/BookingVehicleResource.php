<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingVehicleResource\Pages;
use App\Filament\Resources\BookingVehicleResource\RelationManagers;
use App\Models\BookingVehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingVehicleResource extends Resource
{
    protected static ?string $model = BookingVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $slug = 'pemesanan';
    protected static ?string $label = 'Pemesanan';
    protected static ?int $navigationSort = 1;


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
            'index' => Pages\ListBookingVehicles::route('/'),
            'create' => Pages\CreateBookingVehicle::route('/create'),
            'edit' => Pages\EditBookingVehicle::route('/{record}/edit'),
        ];
    }
}
