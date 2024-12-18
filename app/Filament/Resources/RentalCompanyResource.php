<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalCompanyResource\Pages;
use App\Filament\Resources\RentalCompanyResource\RelationManagers;
use App\Models\RentalCompany;
use Filament\Forms;
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

class RentalCompanyResource extends Resource
{
    protected static ?string $model = RentalCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Perusahaan Rental';
    protected static ?string $slug = 'perusahaan-rental';
    protected static ?string $label = 'Perusahaan Rental';
    protected static ?int $navigationSort = 6;
    
    public static function canViewAny(): bool
    {
        return Auth::user()?->level === 'admin';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 4,
                'md' => 8,
                'lg' => 8,
                'xl' => 12,
            ])
            ->schema([
                TextInput::make('name')
                    ->label('Nama Perusahaan')
                    ->required()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 8,
                        'xl' => 12,
                    ])
                    ->maxLength(100)
                    ->placeholder('Masukkan Nama Perusahaan Rental'),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->maxLength(100)
                    ->placeholder('Masukkan Email'),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->required()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->maxLength(20)
                    ->placeholder('Masukkan Nomor Telepon'),
                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 8,
                        'xl' => 12,
                    ])
                    ->maxLength(255)
                    ->placeholder('Masukkan Alamat Perusahaan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Name'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Phone'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Address')
                    ->limit(50),
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
            'index' => Pages\ListRentalCompanies::route('/'),
        ];
    }
}
