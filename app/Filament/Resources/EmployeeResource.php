<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $slug = 'pegawai';
    protected static ?string $label = 'Pegawai';
    protected static ?int $navigationSort = 8;

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
                    ->label('Nama')
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 8,
                        'xl' => 12,
                    ])
                    ->placeholder('Masukkan Nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->placeholder('Masukkan Email')
                    ->required(),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->placeholder('Masukkan No Telepon')
                    ->tel()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->required(),
                Select::make('office_type')
                    ->label('Perusahaan')
                    ->required()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->options([
                        'pusat' => 'Pusat',
                        'cabang' => 'Cabang',
                    ]),
                Select::make('role')
                    ->label('Status')
                    ->required()
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ])
                    ->options([
                        'staff' => 'Staff',
                        'staff & driver' => 'Staff & Driver',
                    ]),
                Textarea::make('address')
                    ->label('Alamat')
                    ->placeholder('Masukkan Alamat')
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 8,
                        'xl' => 12,
                    ])
                    ->required(),
                Checkbox::make('is_approver')
                    ->label('Verifikator')
                    ->columnSpan([
                        'sm' => 4,
                        'md' => 4,
                        'xl' => 6,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListEmployees::route('/'),
        ];
    }
}
