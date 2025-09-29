<?php

namespace App\Services\Dashboard;

use App\Models\AcademicInvoice;
use App\Models\AcademicYear;

class KeuanganDashboardService
{
    public function getDashboardData()
    {
        $activeSemester = AcademicYear::where('is_active', true)->first();

        $jumlahTagihanBelumLunas = 0;
        $nominalTagihanBelumLunas = 0;
        $pendapatanSemesterIni = 0;

        if ($activeSemester) {
            // Menghitung total tagihan yang belum lunas di semester aktif
            $tagihanBelumLunas = AcademicInvoice::where('academic_year_id', $activeSemester->id)
                                                ->where('status', 'unpaid')
                                                ->get();
            $jumlahTagihanBelumLunas = $tagihanBelumLunas->count();
            $nominalTagihanBelumLunas = $tagihanBelumLunas->sum('total_amount');

            // Menghitung total pendapatan dari tagihan yang sudah lunas di semester aktif
            $pendapatanSemesterIni = AcademicInvoice::where('academic_year_id', $activeSemester->id)
                                                   ->where('status', 'paid')
                                                   ->sum('total_amount');
        }

        return [
            'jumlahTagihanBelumLunas' => $jumlahTagihanBelumLunas,
            'nominalTagihanBelumLunas' => $nominalTagihanBelumLunas,
            'pendapatanSemesterIni' => $pendapatanSemesterIni,
            'activeSemester' => $activeSemester,
        ];
    }
}