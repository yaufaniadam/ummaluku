<?php

namespace App\Services\Dashboard;

use App\Models\Application;
use App\Models\Batch;
use App\Models\ReRegistrationInstallment;
use App\Models\ApplicationProgramChoice;

class PMBDashboardService
{
    public function getDashboardData()
    {
        // Ambil gelombang yang sedang aktif
        $activeBatch = Batch::where('is_active', true)->first();

        $totalPendaftar = 0;
        $pembayaranPending = 0;
        $berkasPending = 0;

        $acceptedButUnpaid = 0;
        $waitingForVerification = 0;
        $readyForSelection = 0;

        $chartLabels = [];
        $chartValues = [];

        $todoList = collect();

        if ($activeBatch) {
            // 1. Existing Widgets
            $totalPendaftar = Application::where('batch_id', $activeBatch->id)->count();

            // Pendaftar yang statusnya masih 'lakukan_pembayaran'
            $pembayaranPending = Application::where('batch_id', $activeBatch->id)
                                            ->where('status', 'lakukan_pembayaran')
                                            ->count();

            // Pendaftar yang statusnya 'lengkapi_data' (menunggu upload berkas)
            $berkasPending = Application::where('batch_id', $activeBatch->id)
                                        ->where('status', 'lengkapi_data')
                                        ->count();

            // 2. New Widgets
            // Jumlah camaru yang diterima, tapi belum membayar (Unpaid Invoice)
            $acceptedButUnpaid = Application::where('batch_id', $activeBatch->id)
                ->where('status', 'diterima')
                ->whereHas('reRegistrationInvoice', function ($q) {
                    $q->where('status', 'unpaid');
                })
                ->count();

            // Jumlah camaru yang menunggu verifikasi (Dokumen)
            // Asumsi: 'proses_verifikasi' adalah status setelah upload dokumen
            $waitingForVerification = Application::where('batch_id', $activeBatch->id)
                ->where('status', 'proses_verifikasi')
                ->count();

            // Jumlah camaru yang masuk proses seleksi
            $readyForSelection = Application::where('batch_id', $activeBatch->id)
                ->where('status', 'lolos_verifikasi_data')
                ->count();

            // 3. Chart Data: Penerimaan mahasiswa baru per tahun angkatan, berdasarkan prodi
            // Kita ambil yang statusnya 'diterima' atau 'sudah_registrasi' di gelombang aktif
            $programStats = ApplicationProgramChoice::query()
                ->whereHas('application', function($q) use ($activeBatch) {
                     $q->where('batch_id', $activeBatch->id)
                       ->whereIn('status', ['diterima', 'sudah_registrasi']);
                })
                ->where('is_accepted', true)
                ->with('program')
                ->get()
                ->groupBy(function($choice) {
                    return $choice->program->name_id;
                })
                ->map->count();

            $chartLabels = $programStats->keys()->toArray();
            $chartValues = $programStats->values()->toArray();

            // 4. To-Do List Table
            // A. Menunggu Verifikasi Dokumen
            $todoDocs = Application::with('prospective.user')
                ->where('batch_id', $activeBatch->id)
                ->where('status', 'proses_verifikasi')
                ->get()
                ->map(function ($app) {
                    return [
                        'type' => 'Verifikasi Dokumen',
                        'name' => $app->prospective->user->name ?? 'N/A',
                        'date' => $app->updated_at,
                        'url' => route('admin.pmb.pendaftaran.show', $app->id),
                        'status_label' => 'Menunggu Verifikasi',
                        'badge' => 'warning',
                    ];
                });

            // B. Menunggu Verifikasi Pembayaran (Daftar Ulang)
            // Kita cari Installment dengan status pending_verification
            $todoPayments = ReRegistrationInstallment::with(['invoice.application.prospective.user'])
                ->where('status', 'pending_verification')
                ->get()
                ->map(function ($installment) {
                    $user = $installment->invoice->application->prospective->user ?? null;
                    return [
                        'type' => 'Verifikasi Pembayaran',
                        'name' => $user ? $user->name : 'Unknown',
                        'date' => $installment->updated_at,
                        'url' => route('admin.pmb.payment.show', $installment->invoice_id),
                        'status_label' => 'Menunggu Verifikasi Pembayaran',
                        'badge' => 'primary',
                    ];
                });

            // Gabung dan sort (yang paling lama menunggu di atas / ASC date)
            $todoList = $todoDocs->merge($todoPayments)->sortBy('date');
        }


        return [
            'activeBatch' => $activeBatch,
            'totalPendaftar' => $totalPendaftar,
            'pembayaranPending' => $pembayaranPending,
            'berkasPending' => $berkasPending,
            'acceptedButUnpaid' => $acceptedButUnpaid,
            'waitingForVerification' => $waitingForVerification,
            'readyForSelection' => $readyForSelection,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'todoList' => $todoList,
        ];
    }
}
