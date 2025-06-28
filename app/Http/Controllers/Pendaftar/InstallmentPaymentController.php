<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\ReRegistrationInstallment;
use App\Models\ReRegistrationInvoice; // <-- Import model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentPaymentController extends Controller
{
    /**
     * Menyimpan bukti pembayaran yang diunggah.
     */
    public function store(Request $request, ReRegistrationInstallment $installment)
    {
        // 1. Otorisasi
        $applicationOwnerId = $installment->invoice->application->prospective->user_id;
        if (Auth::id() !== $applicationOwnerId) {
            abort(403, 'Anda tidak memiliki akses untuk aksi ini.');
        }

        // 2. Validasi file
        $request->validate(['proof_of_payment' => 'required|image|max:2048']);

        // 3. Simpan file
        $filePath = $request->file('proof_of_payment')->store(
            'payment_proofs/re-registration/' . $installment->invoice->application_id, 
            'public'
        );

        // 4. Update record cicilan
        $installment->update([
            'proof_of_payment' => $filePath,
            'status' => 'pending_verification',
        ]);

        // 5. <<< PANGGIL METHOD BARU DI SINI >>>
        // Perbarui status invoice induknya setelah cicilan diupdate
        $this->updateParentInvoiceStatus($installment->invoice);

        // 6. Redirect kembali
        return back()->with('success', 'Bukti pembayaran untuk Cicilan ke-' . $installment->installment_number . ' berhasil diunggah.');
    }

    /**
     * Method "pinjaman" untuk memperbarui status invoice induk.
     */
    protected function updateParentInvoiceStatus(ReRegistrationInvoice $invoice)
    {
        $installments = $invoice->installments()->get();
        $allPaid = $installments->every(fn($item) => $item->status === 'paid');
        $hasPending = $installments->contains(fn($item) => $item->status === 'pending_verification');

        $newStatus = 'unpaid';

        if ($allPaid) {
            $newStatus = 'paid';
        } elseif ($hasPending) {
            $newStatus = 'pending_verification'; // <-- Ini akan terpilih sekarang
        } elseif ($installments->where('status', 'paid')->isNotEmpty()) {
            $newStatus = 'partially_paid';
        }

        $invoice->update(['status' => $newStatus]);
    }
}