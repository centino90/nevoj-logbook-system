<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RankedProfessors extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Professors Ranked by Served Transactions';

    protected function getTableQuery(): Builder
    {
        return User::query()->onlyProfessors()->withCount('servedTransactions')->orderByDesc('served_transactions_count');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('name')
                ->label('Customer'),
            Tables\Columns\TextColumn::make('totalServedTransactions')
            ->getStateUsing( function (Model $record){
                return $record->servedTransactions->count();
            })->searchable(['name', 'id'])
        ];
    }
}
