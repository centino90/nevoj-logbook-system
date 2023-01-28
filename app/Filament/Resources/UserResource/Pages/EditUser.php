<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Phpsa\FilamentAuthentication\Events\UserUpdated;

class EditUser extends EditRecord
{
    public static function getResource(): string
    {
        return Config::get('filament-authentication.resources.UserResource');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        Event::dispatch(new UserUpdated($this->record));
    }

    protected function getForms(): array
    {
        $formSchema = $this->getFormSchema();
        $record = $this->getRecord();

        // if admin count is low, override resource form to remove role text field
        $hasLowAdminCount = $record->whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->count() <= 1;
        if($hasLowAdminCount && $record->hasAnyRole('admin')) {
            $resourceForm = $this->getResourceForm(columns: config('filament.layout.forms.have_inline_labels') ? 1 : 2);
            $resourceForm->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.name')))
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->unique(table: $this->getRecord()->getTable(), ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),

                    ])->columns(2),
            ]);
            $formSchema = $resourceForm->getSchema();
        }

        return [
            'form' => $this->makeForm()
                ->context('edit')
                ->model($this->getRecord())
                ->schema($formSchema)
                ->statePath('data')
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }
}
