<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function form(Form $form): Form
    {
        $form = $form
        ->schema([
            Card::make()
                ->schema([
                    TextInput::make('data.title')
                        ->label('title'),
                    KeyValue::make('data.body')
                        ->keyLabel('User Id')
                        ->valueLabel('New Password')
                        ->formatStateUsing(function ($state) {
                            $s = json_decode($state);
                            if(is_array($s)) {
                                $d = array();

                                foreach ($s as $index => $value) {
                                    $d[$value->userId] = $value->password;
                                }

                                return $d;
                            } else {
                                return [$s->userId => $s->password];
                            }
                        })
                        ->label('body')
                ])->columns(1),
        ]);

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime('M-d-y g:i:s A')
                ->sortable()
                ->label('timestamp'),
                TextColumn::make('data.title')
                // ->formatStateUsing(fn ($state) => dd($state))
                ->label('title'),
                TextColumn::make('data.body')
                // ->formatStateUsing(fn ($state) => json_decode($state)->body)
                ->label('body'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
            'view' => Pages\ViewNotification::route('/{record}')
        ];
    }
}
