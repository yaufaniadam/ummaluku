<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicInvoice;
use App\Models\AcademicPayment; 
use Illuminate\Http\Request;

class PaymentConfirmationController extends Controller
{
    // Method untuk menampilkan form
    public function create(AcademicInvoice $invoice)
    {
        // Keamanan: Pastikan invoice ini milik mahasiswa yang sedang login
        if ($invoice->student_id !== auth()->user()->student->id || $invoice->status !== 'unpaid') {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }
        return view('mahasiswa.keuangan.confirm', compact('invoice'));
    }

    // Method untuk menyimpan data form
    public function store(Request $request, AcademicInvoice $invoice)
    {
        if ($invoice->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:' . $invoice->total_amount,
            'proof' => 'required|image|max:2048', // Maks 2MB
            'notes' => 'nullable|string',
        ]);

        // Simpan file bukti pembayaran ke storage/app/public/payment_proofs
        $filePath = $request->file('proof')->store('payment_proofs', 'public');

        // Buat record pembayaran baru
        AcademicPayment::create([
            'academic_invoice_id' => $invoice->id,
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'proof_url' => $filePath,
            'notes' => $validated['notes'],
            'status' => 'pending', // Status awal pembayaran adalah 'pending'
        ]);

        // Update status invoice menjadi 'menunggu_verifikasi'
        $invoice->update(['status' => 'verify']);

        return redirect()->route('mahasiswa.keuangan.show', $invoice->id)
                         ->with('success', 'Konfirmasi pembayaran berhasil diunggah dan sedang menunggu verifikasi.');
    }
}