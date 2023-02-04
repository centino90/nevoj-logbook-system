<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class CreateTransaction extends ModalComponent implements HasForms
{
    use InteractsWithForms;

    public $visitor_name = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Create New Entry')
                ->schema([
                        TextInput::make('visitor_name')
                            ->label('Visitor Name')
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('course')
                            ->label('Course')
                            ->required(),
                            TextInput::make('purpose')
                            ->label('Purpose')
                            ->required(),
                            Select::make('user_id')
                            ->label('Faculty Concerned')
                            ->options(User::whereHas('roles', function(Builder $query) {
                                return $query->where('name', 'professor');
                            })->pluck('name', 'id')->toArray())
                            ->required(),
            ])->columns([
                'sm' => 1,
                'xl' => 2])
        ];
    }

    public function submit() {
        $transaction = new Transaction;
        $transaction->visitor_name = $this->data['visitor_name'];
        $transaction->course = $this->data['course'];
        $transaction->purpose = $this->data['purpose'];
        $transaction->user_id = $this->data['user_id'];
        $transaction->save();

        $this->closeModal();
        $this->emit('refreshTransactions');

        $professor = User::find($transaction->user_id);
        if($professor) {
            $professor->notify(
                Notification::make()
                    ->title($transaction->visitor_name . ' logged their transaction')
                    ->body(collect(['id' => $transaction->id, 'purpose' => $transaction->course, 'course' => $transaction->course])->toJson())
                    ->actions([
                        NotificationAction::make('view')
                        ->button()
                        ->url(route('filament.resources.transactions.edit', $transaction), shouldOpenInNewTab: false)
                    ])
                    ->toDatabase(),
            );
        }
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }


    public function render()
    {
        return view('livewire.create-transaction');
    }
}
