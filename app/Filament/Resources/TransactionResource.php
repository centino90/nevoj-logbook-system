<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Filters\ServedFilter;
use App\Filament\Resources\Filters\TrashedFilter;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Scopes\TransactionByRoleScope;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('course')
                ->label('Course')
                ->required(),
                TextInput::make('visitor_name')
                ->label('Visitor')
                ->required(),
                TextInput::make('purpose')
                ->label('Purpose')
                ->required(),
                Select::make('user_id')
                ->label('Faculty Concerned')
                ->relationship('user', 'name', function(Builder $query) {
                    return $query->whereHas('roles', function(Builder $query) {
                        return $query->where('name', 'professor');
                    });
                })
                ->preload(true)
                ->required(),
                Toggle::make('served_at')
                ->label('Served'),
                Textarea::make('note')
                ->label('Note')
                ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                ->dateTime('M-d-y g:i:s A')
                ->sortable()
                ->label('Creation Date'),
                IconColumn::make('served_at')
                ->options([
                    'heroicon-o-x-circle' => fn ($served_at) => empty($served_at),
                    'heroicon-o-check-circle' => fn ($served_at) => !empty($served_at),
                ])
                ->colors([
                    'secondary' => fn ($served_at) => empty($served_at),
                    'success' => fn ($served_at) => !empty($served_at),
                ])
                ->label('Served'),
                TextColumn::make('course')
                ->label('Course')
                ->searchable(),
                TextColumn::make('visitor_name')
                ->sortable()
                ->searchable()
                ->label('Visitor Name'),
                TextColumn::make('purpose')
                ->label('Purpose')
                ->searchable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                ServedFilter::make('served_at')
                ->label('Served records')
                ->default(0),
                TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                RestoreBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withGlobalScope('TransactionByRoleScope', TransactionByRoleScope::class)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            // 'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('served_at', null)->count();
    }
}
