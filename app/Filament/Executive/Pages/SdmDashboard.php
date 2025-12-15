<?php

namespace App\Filament\Executive\Pages;

use Filament\Pages\Page;
use App\Filament\Executive\Widgets\LecturerStatusChart;

class SdmDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'SDM (Dosen)';
    protected static ?string $navigationGroup = 'Executive Modules';
    protected static ?string $title = 'Dashboard SDM';
    protected static string $view = 'filament.executive.pages.sdm-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            LecturerStatusChart::class,
        ];
    }
}
