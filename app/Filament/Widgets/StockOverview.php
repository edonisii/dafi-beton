<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use App\Models\StockMovement;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $materials = Material::query()->withCurrentStock()->get();

        $lowStock = $materials->filter(fn (Material $m): bool => $m->isLowStock());

        $thisMonth = now();
        $lastMonth = now()->subMonthNoOverflow();

        $purchasesThisMonth = $this->sumFor(StockMovement::TYPE_IN, $thisMonth);
        $salesThisMonth = $this->sumFor(StockMovement::TYPE_OUT, $thisMonth);
        $salesLastMonth = $this->sumFor(StockMovement::TYPE_OUT, $lastMonth);

        $up = $salesThisMonth >= $salesLastMonth;

        return [
            Stat::make('Nën stok minimal', $lowStock->count())
                ->description($lowStock->isEmpty() ? 'Të gjitha në rregull' : $lowStock->pluck('name')->implode(', '))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStock->isEmpty() ? 'success' : 'danger'),

            Stat::make('Shitje këtë muaj', '€ ' . number_format($salesThisMonth, 2))
                ->description('Muaji i kaluar: € ' . number_format($salesLastMonth, 2))
                ->descriptionIcon($up ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($up ? 'success' : 'danger'),

            Stat::make('Shitje muajin e kaluar', '€ ' . number_format($salesLastMonth, 2))
                ->description('Vlera totale e daljeve/shitjeve')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('gray'),

            Stat::make('Blerje këtë muaj', '€ ' . number_format($purchasesThisMonth, 2))
                ->description('Vlera totale e hyrjeve')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('primary'),
        ];
    }

    protected function sumFor(string $type, Carbon $month): float
    {
        return (float) StockMovement::query()
            ->where('type', $type)
            ->whereMonth('occurred_on', $month->month)
            ->whereYear('occurred_on', $month->year)
            ->sum('total_price');
    }
}
