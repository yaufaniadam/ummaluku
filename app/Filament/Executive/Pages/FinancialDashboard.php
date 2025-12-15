<?php

namespace App\Filament\Executive\Pages;

use Filament\Pages\Page;
use App\Filament\Executive\Widgets\FinancialStatsOverview;

class FinancialDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Keuangan';
    protected static ?string $navigationGroup = 'Executive Modules';
    protected static ?string $title = 'Dashboard Keuangan';
    protected static string $view = 'filament.executive.pages.financial-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            FinancialStatsOverview::class,
        ];
    }
}
