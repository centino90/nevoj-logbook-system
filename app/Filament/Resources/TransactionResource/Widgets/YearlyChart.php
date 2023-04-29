<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Scopes\TransactionByRoleScope;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class YearlyChart extends BarChartWidget
{
    protected static ?string $heading = 'Chart';

    // protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // top 5 professor by total served transactions count in current year
        $data = User::getTopFiveProfessorsByTransactionsInCurrentYear();

        return [
            'datasets' => [
                [
                    'label' => 'Served',
                    'data' => $data->map(fn ($d) => $d->served_transactions_count),
                    'indexAxis' => 'y',
                    'backgroundColor' => '#84cc16'
                ],
                [
                    'label' => 'Pending',
                    'data' => $data->map(fn ($d) => $d->unserved_transactions_count),
                    'indexAxis' => 'y',
                    'backgroundColor' => '#f87171'
                ]
            ],
            'labels' => $data->map(fn ($d) => $d->name),
        ];
    }

    protected function getHeading(): string
    {
        return 'Top 5 Most Transactions this Year';
    }
}
