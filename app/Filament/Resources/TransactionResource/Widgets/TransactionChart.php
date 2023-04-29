<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Scopes\TransactionByRoleScope;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TransactionChart extends BarChartWidget
{
    protected static ?string $heading = 'Chart';

    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {

        // $data = Trend::query(User::withCount('transactions'))
        // ->between(
        //     start: now()->startOfYear(),
        //     end: now()->endOfYear(),
        // )
        // ->perWeek()
        // ->max('name');


        // top 5 professor by total served transactions count
        $data = User::getTopFiveProfessorsWithHighestServedTransactions();
        // dd($data);

        // top 5 professor by total served transactions count in current week
        // dd(now()->format('W'));
        // dd(User::getTopFiveProfessorsWithHighestServedTransactionsInCurrentYear());

        // top 5 professor by total served transactions count in current month
        // dd(User::getTopFiveProfessorsWithHighestServedTransactions());

        // top 5 professor by total served transactions count in current year
        // dd(User::getTopFiveProfessorsWithHighestServedTransactions());

        // top 5 professor by served transactions count
        // dd(User::getTopFiveProfessorsWithHighestServedTransactions());

        // $data = Trend::query(User::withCount('transactions')->groupBy('id'))
        // ->between(
        //     start: now()->startOfYear(),
        //     end: now()->endOfYear(),
        // )
        // ->perMonth()
        // ->max('name');

        // dd($data);
        // dd(Transaction::withoutGlobalScopes([TransactionByRoleScope::class])->select(DB::raw('count("user_id") AS count'), 'user_id')->groupBy('user_id')->orderByDesc('count')->first());

        // $rd = Transaction::withoutGlobalScopes([TransactionByRoleScope::class])->where('user_id', function($query) {
        //     $query->select(DB::raw(1))
        //     ->from('orders')
        //     ->whereRaw('orders.user_id = users.id');
        // });
        // ->select(DB::raw('count("user_id") AS count'), 'user_id')->groupBy('user_id')->orderByDesc('count')->first()
        // dd(Transaction::withoutGlobalScopes([TransactionByRoleScope::class])->get());

        // $data = Trend::query(Transaction::where())
        // ->between(
        //     start: now()->startOfYear(),
        //     end: now()->endOfYear(),
        // )
        // ->perMonth()
        // ->count();

        // dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Served Transactions',
                    'data' => $data->map(fn ($d) => $d->served_transactions_count),
                    'indexAxis' => 'y'
                ],
            ],
            'labels' => $data->map(fn ($d) => $d->name),
        ];
    }

    protected function getHeading(): string
    {
        return 'Five Most Served Professors';
    }
}
