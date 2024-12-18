<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Filament\Resources\LogResource\RelationManagers;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Rmsramos\Activitylog\Resources\ActivitylogResource;

class LogResource extends ActivitylogResource
{
    public static function canViewAny(): bool
    {
        return Auth::user()?->level === 'admin';
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                static::getLogNameColumnCompoment(),
                static::getEventColumnCompoment()->label('Aksi'),
                static::getSubjectTypeColumnCompoment(),
                static::getCauserNameColumnCompoment(),
                static::getPropertiesColumnCompoment(),
                static::getCreatedAtColumnCompoment()->label('Waktu Dibuat'),
            ])
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterCompoment(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
            'view' => Pages\ViewLog::route('/{record}'),
        ];
    }
}
