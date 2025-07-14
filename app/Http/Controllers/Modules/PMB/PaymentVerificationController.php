<?php

namespace App\Http\Controllers\Modules\PMB;

use App\DataTables\ReRegistrationInvoicesDataTable;
use App\Http\Controllers\Controller;
use App\Models\ReRegistrationInvoice;
use App\Models\ReRegistrationInstallment;
use Illuminate\Http\Request;
use App\Notifications\PembayaranCicilanDiterima;
use App\Notifications\PembayaranLunas;

class PaymentVerificationController extends Controller
{
    public function index(ReRegistrationInvoicesDataTable $dataTable)
    {
        $statuses = [
            'unpaid' => 'Belum Dibayar',
            'partially_paid' => 'Dibayar Sebagian',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'rejected' => 'Ditolak',
        ];

        return $dataTable->render('admin.pmb.payment.index', compact('statuses'));
    }

    public function show(ReRegistrationInvoice $invoice)
    {
        // Load relasi yang dibutuhkan agar efisien
        $invoice->load('application.prospective.user', 'installments');
        return view('admin.pmb.payment.show', compact('invoice'));
    }

    /**
     * Menyetujui satu cicilan pembayaran.
     */
    public function approveInstallment(ReRegistrationInstallment $installment)
    {
        $installment->update([
            'status' => 'paid',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $user = $installment->invoice->application->prospective->user;

        // Cek apakah semua cicilan lain sudah lunas
        $allPaid = $installment->invoice->installments()->where('status', '!=', 'paid')->count() === 0;

        
        if ($allPaid) {
            $installment->invoice->update(['status' => 'paid']);
            // --- BARIS BARU: Kirim notifikasi pembayaran lunas ---
            $user->notify(new PembayaranLunas($installment->invoice));
        } else {
            $installment->invoice->update(['status' => 'partially_paid']);
            // --- BARIS BARU: Kirim notifikasi cicilan diterima ---
            $user->notify(new PembayaranCicilanDiterima($installment));
        }

        return back()->with('success', 'Cicilan berhasil diverifikasi.');
    }

    /**
     * Menolak satu cicilan pembayaran.
     */
    public function rejectInstallment(Request $request, ReRegistrationInstallment $installment)
    {
        // $request->validate(['notes' => 'required|string|max:255']);

        $installment->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            // 'notes' => $request->input('notes'), // Simpan catatan penolakan
            'notes' => 'Catatan', // Simpan catatan penolakan
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Cicilan telah ditolak dengan catatan.');
    }
}