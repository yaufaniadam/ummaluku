<?php

namespace App\Filament\Executive\Pages;

use Filament\Pages\Page;
use App\Filament\Executive\Widgets\StudentsPerProgramChart;

class AcademicDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Akademik';
    protected static ?string $navigationGroup = 'Executive Modules';
    protected static ?string $title = 'Dashboard Akademik';
    protected static string $view = 'filament.executive.pages.academic-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            StudentsPerProgramChart::class,
        ];
    }
}
