<?php

namespace App\Filament\Executive\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Application;

class PmbStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftar', Application::count()),
            Stat::make('Lulus Seleksi', Application::where('status', 'diterima')->count())
                ->color('success'),
            Stat::make('Sudah Registrasi', Application::where('status', 'sudah_registrasi')->count())
                ->description('Mahasiswa Baru')
                ->color('primary'),
        ];
    }
}
