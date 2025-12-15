<?php

namespace App\Filament\Executive\Widgets;

use App\Models\Student;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Application;
use App\Models\Program;
use App\Models\Faculty;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExecutiveStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Mahasiswa', Student::count()),
            Stat::make('Mahasiswa Aktif', Student::where('status', 'Aktif')->count()),
            Stat::make('Total Dosen', Lecturer::count()),
            Stat::make('Total Tendik', User::role('Tendik')->count()),
            Stat::make('Total Pendaftar', Application::count()),
            Stat::make('Diterima', Application::where('status', 'diterima')->orWhere('status', 'sudah_registrasi')->count()),
            Stat::make('Fakultas', Faculty::count()),
            Stat::make('Program Studi', Program::count()),
        ];
    }
}
