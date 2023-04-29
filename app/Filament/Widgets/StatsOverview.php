<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getCards(): array
    {
        $totalTransactions = Transaction::count();

        $week = Transaction::getTransactionsPreviousAndCurrentWeek();
        $weekPercentage = ($week['current'] > $week['previous']) ? $week['current'] - $week['previous'] : (($week['current'] < $week['previous']) ? $week['previous'] - $week['current'] : 0);
        $weekPercentageLabel = ($week['current'] > $week['previous']) ? 'increase' : (($week['previous'] > $week['current']) ? 'decrease' : '');
        $weekPercentageText = $weekPercentage ? $weekPercentage . ' ' . $weekPercentageLabel : 'no increase/decrease';
        $weekPercentageIcon = ($week['current'] > $week['previous']) ? 'heroicon-s-trending-up' : (($week['previous'] > $week['current']) ? 'heroicon-s-trending-down' : '');
        $weekPercentageColor = ($week['current'] > $week['previous']) ? 'success' : (($week['previous'] > $week['current']) ? 'danger' : '');

        $month = Transaction::getTransactionsPreviousAndCurrentMonth();
        $monthPercentage = ($month['current'] > $month['previous']) ? $month['current'] - $month['previous'] : (($month['current'] < $month['previous']) ? $month['previous'] - $month['current'] : 0);
        $monthPercentageLabel = ($month['current'] > $month['previous']) ? 'increase' : (($month['previous'] > $month['current']) ? 'decrease' : '');
        $monthPercentageText = $monthPercentage ? $monthPercentage . ' ' . $monthPercentageLabel : 'no increase/decrease';
        $monthPercentageIcon = ($month['current'] > $month['previous']) ? 'heroicon-s-trending-up' : (($month['previous'] > $month['current']) ? 'heroicon-s-trending-down' : '');
        $monthPercentageColor = ($month['current'] > $month['previous']) ? 'success' : (($month['previous'] > $month['current']) ? 'danger' : '');

        $year = Transaction::getTransactionsPreviousAndCurrentYear();
        $yearPercentage = ($year['current'] > $year['previous']) ? $year['current'] - $year['previous']: (($year['current'] < $year['previous']) ? $year['previous'] - $year['current'] : 0);
        $yearPercentageLabel = ($year['current'] > $year['previous']) ? 'increase' : (($year['previous'] > $year['current']) ? 'decrease' : '');
        $yearPercentageText = $yearPercentage ? $yearPercentage . ' ' . $yearPercentageLabel : 'no increase/decrease';
        $yearPercentageIcon = ($year['current'] > $year['previous']) ? 'heroicon-s-trending-up' : (($year['previous'] > $year['current']) ? 'heroicon-s-trending-down' : '');
        $yearPercentageColor = ($year['current'] > $year['previous']) ? 'success' : (($year['previous'] > $year['current']) ? 'danger' : '');


        return [
            Card::make('Total transactions', $totalTransactions),
            Card::make('Weekly transactions', $week['current'])
                ->description($weekPercentageText)
                ->descriptionIcon($weekPercentageIcon)
                ->color($weekPercentageColor),
            Card::make('Monthly transactions', $month['current'])
                ->description($monthPercentageText)
                ->descriptionIcon($monthPercentageIcon)
                ->color($monthPercentageColor),
            Card::make('Yearly transactions', $year['current'])
                ->description($yearPercentageText)
                ->descriptionIcon($yearPercentageIcon)
                ->color($yearPercentageColor),
        ];
    }
}
