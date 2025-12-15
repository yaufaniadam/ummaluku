<?php

namespace App\Filament\Executive\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\AcademicInvoice;
use App\Models\AcademicPayment;

class FinancialStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tagihan', 'Rp ' . number_format(AcademicInvoice::sum('total_amount'), 0, ',', '.'))
                ->description('Semua tagihan terbit'),

            Stat::make('Total Terbayar', 'Rp ' . number_format(AcademicInvoice::sum('paid_amount'), 0, ',', '.'))
                ->description('Pemasukan realisasi')
                ->color('success'),

            Stat::make('Tunggakan', 'Rp ' . number_format(AcademicInvoice::where('status', 'unpaid')->sum('balance'), 0, ',', '.'))
                ->description('Tagihan belum lunas')
                ->color('danger'),
        ];
    }
}
