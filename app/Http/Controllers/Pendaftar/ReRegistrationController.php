<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\ReRegistrationInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReRegistrationController extends Controller
{
    public function show()
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user();

        // 2. Cari tagihan registrasi ulang milik user ini
        $invoice = ReRegistrationInvoice::whereHas('application.prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        // 3. Penjaga Gerbang: Jika tidak ada tagihan, atau statusnya bukan 'accepted'
        if (!$invoice || $invoice->application->status !== 'accepted') {
            // Arahkan ke dashboard dengan pesan error
            return redirect()->route('pendaftar.dashboard')->with('error', 'Anda belum bisa mengakses halaman registrasi ulang saat ini.');
        }

        // 4. Jika lolos, tampilkan view dan kirim data tagihan
        return view('pendaftar.registrasi', ['invoice' => $invoice]);
    }

    public function choosePaymentScheme(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:re_registration_invoices,id',
            'payment_scheme' => 'required|in:full,installment',
        ]);

        $invoice = ReRegistrationInvoice::find($request->invoice_id);
        $totalAmount = $invoice->total_amount;

        // Hapus cicilan lama jika ada (untuk kasus memilih ulang)
        $invoice->installments()->delete();

        if ($request->payment_scheme === 'full') {
            // Jika bayar lunas, buat 1 cicilan
            $invoice->installments()->create([
                'installment_number' => 1,
                'amount' => $totalAmount,
                'due_date' => now()->addWeeks(2),
            ]);
        } elseif ($request->payment_scheme === 'installment') {
            // Jika cicilan, buat 2 cicilan
            $invoice->installments()->create([
                'installment_number' => 1,
                'amount' => $totalAmount * 0.5, // 50%
                'due_date' => now()->addWeeks(2),
            ]);
            $invoice->installments()->create([
                'installment_number' => 2,
                'amount' => $totalAmount * 0.5, // 50%
                'due_date' => now()->addMonths(2),
            ]);
        }

        return back()->with('success', 'Skema pembayaran berhasil dipilih. Silakan lanjutkan pembayaran.');
    }
}
