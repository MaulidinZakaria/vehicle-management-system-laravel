<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $slug = 'pengguna';
    protected static ?string $label = 'Pengguna';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Masukkan Nama'),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(100)
                    ->placeholder('Masukkan Email'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->placeholder(fn($context) => $context === 'view' ? '********' : 'Masukkan Password')
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state('');
                    })
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->helperText(function ($state, $context) {
                        // Show a helper text if in 'edit' context and no password is provided
                        if ($context === 'edit' && empty($state)) {
                            return 'Kosongi jika tidak mengubah password';
                        }
                        return '';
                    }),
                Select::make('position')
                    ->label('Posisi')
                    ->required()
                    ->options([
                        'Admin' => 'Admin',
                        'Supervisor' => 'Supervisor',
                        'Team Leader' => 'Team Leader',
                        'Manager' => 'Manager',
                        'Direktur' => 'Direktur',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        // Update level berdasarkan posisi
                        $level = match ($state) {
                            'Admin' => 'admin',
                            'Supervisor' => 'approver_level_1',
                            'Team Leader' => 'approver_level_1',
                            'Manager' => 'approver_level_2',
                            'Direktur' => 'approver_level_2',
                            default => null,
                        };

                        // Set level berdasarkan hasil match
                        $set('level', $level);
                    }),
                TextInput::make('level')
                    ->label('Level')
                    ->required()
                    ->placeholder('-')
                    ->readOnly(),
                Select::make('office_type')
                    ->label('Perusahaan')
                    ->required()
                    ->options([
                        'pusat' => 'Pusat',
                        'cabang' => 'Cabang',
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
                TextColumn::make('position')
                    ->searchable()
                    ->label('Jabatan'),
                TextColumn::make('level')
                    ->searchable()
                    ->label('Level')
                    ->sortable(),
                TextColumn::make('office_type')
                    ->searchable()
                    ->label('Perusahaan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->label('Jabatan')
                    ->options([
                        'Admin' => 'Admin',
                        'Supervisor' => 'Supervisor',
                        'Team Leader' => 'Team Leader',
                        'Manager' => 'Manager',
                        'Direktur' => 'Direktur',
                    ]),
                SelectFilter::make('level')
                    ->label('Level')
                    ->options([
                        'admin' => 'Admin',
                        'approver_level_1' => 'Approver Level 1',
                        'approver_level_2' => 'Approver Level 2',
                    ]),
                SelectFilter::make('office_type')
                    ->label('Perusahaan')
                    ->options([
                        'pusat' => 'Pusat',
                        'cabang' => 'Cabang',
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
