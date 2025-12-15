<?php

namespace App\Filament\Executive\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Program;

class StudentsPerProgramChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Mahasiswa per Prodi';

    protected static ?int $sort = 2; // Tampilkan setelah StatsOverview

    protected function getData(): array
    {
        // Hitung hanya mahasiswa dengan status 'Aktif'
        $programs = Program::withCount(['students' => function ($query) {
            $query->where('status', 'Aktif');
        }])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Mahasiswa Aktif',
                    'data' => $programs->pluck('students_count')->toArray(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $programs->pluck('name_id')->toArray(), // Menggunakan nama Indonesia
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
