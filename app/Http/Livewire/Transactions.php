<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Transactions extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected $listeners = ['refreshTransactions' => 'render'];

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            // ...
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Transaction::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')
            ->dateTime('M-d-y g:i:s A')
            ->label('Creation Date'),
            TextColumn::make('course')
            ->label('Course')
            ->searchable(),
            TextColumn::make('purpose')
            ->label('Purpose')
            ->searchable(),
            TextColumn::make('user.name')
            ->label('Professor Concerned')
            ->searchable()
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableFilters(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-bookmark';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No posts yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a post using the button below.';
    }

    protected function getTablePollingInterval(): ?string
    {
        return '10s';
    }

    public function render(): View
    {
        return view('livewire.transactions')
        ->layout('layouts.welcome');
    }
}
