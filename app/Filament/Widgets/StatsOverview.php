<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {   
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $pemasukkan = Transaction::incomes()
                ->whereBetween('date_transaction', [$startDate , $endDate])
                ->sum('amount');
        $pengeluaran = Transaction::expenses()
                ->whereBetween('date_transaction', [$startDate , $endDate])
                ->sum('amount');

        return [
            Stat::make('Total Pemasukan', $pemasukkan),
            Stat::make('Total Pengeluaran', $pengeluaran),
            Stat::make('Selisih', $pemasukkan - $pengeluaran),
        ];
    }
}