<?php

namespace App\Filament\Executive\Pages;

use Filament\Pages\Page;
use App\Filament\Executive\Widgets\PmbStatsOverview;

class PmbDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'PMB (Penerimaan)';
    protected static ?string $navigationGroup = 'Executive Modules';
    protected static ?string $title = 'Dashboard PMB';
    protected static string $view = 'filament.executive.pages.pmb-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            PmbStatsOverview::class,
        ];
    }
}
