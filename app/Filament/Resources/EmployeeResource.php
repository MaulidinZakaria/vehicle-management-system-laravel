<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $slug = 'pegawai';
    protected static ?string $label = 'Pegawai';
    protected static ?int $navigationSort = 8;

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
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Telepon'),
                TextColumn::make('office_type')
                    ->searchable()
                    ->label('Perusahaan'),
                TextColumn::make('role')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'staff' => 'warning',
                        'staff & driver' => 'info',
                        default => 'info',
                    }),

            ])
            ->filters([
                SelectFilter::make('office_type')
                    ->label('Perusahaan')
                    ->options([
                        'pusat' => 'Pusat',
                        'cabang' => 'Cabang',
                    ]),
                SelectFilter::make('role')
                    ->label('Status')
                    ->options([
                        'staff' => 'Staff',
                        'staff & driver' => 'Staff & Driver',
                    ]),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
