<?php

namespace App\Services\Dashboard;

use App\Models\Application;
use App\Models\Batch;

class PMBDashboardService
{
    public function getDashboardData()
    {
        // Ambil gelombang yang sedang aktif
        $activeBatch = Batch::where('is_active', true)->first();

        $totalPendaftar = 0;
        $pembayaranPending = 0;
        $berkasPending = 0;

        if ($activeBatch) {
            // Total pendaftar di gelombang aktif
            $totalPendaftar = Application::where('batch_id', $activeBatch->id)->count();

            // Pendaftar yang statusnya masih 'lakukan_pembayaran'
            $pembayaranPending = Application::where('batch_id', $activeBatch->id)
                                            ->where('status', 'lakukan_pembayaran')
                                            ->count();

            // Pendaftar yang statusnya 'lengkapi_data' (menunggu upload berkas)
            $berkasPending = Application::where('batch_id', $activeBatch->id)
                                        ->where('status', 'lengkapi_data')
                                        ->count();
        }


        return [
            'totalPendaftar' => $totalPendaftar,
            'pembayaranPending' => $pembayaranPending,
            'berkasPending' => $berkasPending,
            'activeBatch' => $activeBatch,
        ];
    }
}