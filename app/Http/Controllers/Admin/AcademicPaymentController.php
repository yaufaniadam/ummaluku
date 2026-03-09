<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\AcademicInvoiceDataTable;
use App\Models\AcademicPayment; 
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;

class AcademicPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AcademicInvoiceDataTable $dataTable)
    {
        return $dataTable->render('admin.keuangan.payment-verification.index');
    }

    // --- METHOD BARU UNTUK MENYETUJUI PEMBAYARAN ---
    public function approve(AcademicPayment $payment)
    {
        // Update status pembayaran menjadi 'verified'
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update status invoice menjadi 'paid' (Lunas)
        $invoice = $payment->academicInvoice;
        $invoice->update(['status' => 'paid']);

        // Update status mahasiswa menjadi 'active'
        $invoice->student->update(['status' => 'active']);

        // --- BARIS BARU: Buat Transaksi Masuk (Auto-Sync) ---
        TransactionService::recordPayment(
            $payment,
            $payment->amount,
            'Pembayaran Invoice #' . $invoice->invoice_number . ' oleh ' . $invoice->student->name
        );

        // Kirim notifikasi ke mahasiswa (bisa ditambahkan nanti)

        return back()->with('success', 'Pembayaran untuk invoice ' . $invoice->invoice_number . ' telah diverifikasi.');
    }

    // --- METHOD BARU UNTUK MENOLAK PEMBAYARAN ---
    public function reject(AcademicPayment $payment)
    {
        // Update status pembayaran menjadi 'rejected'
        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Kembalikan status invoice menjadi 'unpaid' (Belum Lunas) agar mahasiswa bisa upload ulang
        $invoice = $payment->academicInvoice;
        $invoice->update(['status' => 'unpaid']);
        
        // Kirim notifikasi penolakan ke mahasiswa (bisa ditambahkan nanti)

        return back()->with('success', 'Pembayaran untuk invoice ' . $invoice->invoice_number . ' telah ditolak.');
    }

}
