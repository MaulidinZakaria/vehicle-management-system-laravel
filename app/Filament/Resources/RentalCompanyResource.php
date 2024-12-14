<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalCompanyResource\Pages;
use App\Filament\Resources\RentalCompanyResource\RelationManagers;
use App\Models\RentalCompany;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RentalCompanyResource extends Resource
{
    protected static ?string $model = RentalCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Perusahaan Rental';
    protected static ?string $slug = 'perusahaan-rental';
    protected static ?string $label = 'Perusahaan Rental';
    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->level === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Enter the name of the rental company'),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(100)
                    ->placeholder('Enter the email of the rental company'),
                TextInput::make('phone')
                    ->label('Phone')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Enter the phone of the rental company'),
                TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter the address of the rental company'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Name')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email')
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Phone')
                    ->sortable(),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Address')
                    ->sortable(),
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
            'index' => Pages\ListRentalCompanies::route('/'),
            'create' => Pages\CreateRentalCompany::route('/create'),
            'edit' => Pages\EditRentalCompany::route('/{record}/edit'),
        ];
    }
}
