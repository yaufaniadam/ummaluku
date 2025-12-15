<?php

namespace App\Filament\Executive\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Lecturer;

class LecturerStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Keaktifan Dosen';

    protected function getData(): array
    {
        $activeCount = Lecturer::where('status', 'active')->count();
        $inactiveCount = Lecturer::where('status', '!=', 'active')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Dosen',
                    'data' => [$activeCount, $inactiveCount],
                    'backgroundColor' => ['#10b981', '#ef4444'],
                ],
            ],
            'labels' => ['Aktif', 'Tidak Aktif/Cuti'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
