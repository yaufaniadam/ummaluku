<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicInvoice; 

class KeuanganController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        // Ambil semua invoice milik mahasiswa ini, urutkan dari yang terbaru
        $invoices = $student->academicInvoices()
                            ->with('academicYear')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Ambil tagihan registrasi ulang (jika ada)
        $reRegistrationInvoice = \App\Models\ReRegistrationInvoice::whereHas('application.prospective', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })->first();

        return view('mahasiswa.keuangan.index', compact('invoices', 'reRegistrationInvoice'));
    }

    public function showReRegistration()
    {
        $user = Auth::user();
        $invoice = \App\Models\ReRegistrationInvoice::whereHas('application.prospective', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['application.admissionCategory', 'application.batch', 'installments'])->first();

        if (!$invoice) {
            abort(404, 'Tagihan registrasi ulang tidak ditemukan.');
        }

        return view('mahasiswa.keuangan.re-registration', compact('invoice'));
    }

    public function show(AcademicInvoice $invoice)
    {
        // Keamanan: Pastikan invoice ini milik mahasiswa yang sedang login
        if ($invoice->student_id !== auth()->user()->student->id) {
            abort(403, 'Anda tidak berhak mengakses tagihan ini.');
        }

        // Eager load relasi 'items' untuk menampilkan rincian
        $invoice->load('items');

        return view('mahasiswa.keuangan.show', compact('invoice'));
    }
}