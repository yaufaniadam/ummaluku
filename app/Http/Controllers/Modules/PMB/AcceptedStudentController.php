<?php

namespace App\Http\Controllers\Modules\PMB;

use App\DataTables\AcceptedStudentsDataTable;
use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\Batch;
use App\Models\Program;
use App\Models\Application;
use App\Notifications\MahasiswaDiterima; 

class AcceptedStudentController extends Controller
{
    public function index(AcceptedStudentsDataTable $dataTable)
    {
        $categories = AdmissionCategory::all();
        $batches = Batch::all();
        $programs = Program::all();

        // Data baru untuk filter status pembayaran
        $payment_statuses = [
            'unpaid' => 'Belum Dibayar',
            'partially_paid' => 'Dibayar Sebagian',
            'pending_verification' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
        ];


        return $dataTable->render('admin.accepted.index', compact('categories', 'batches', 'programs', 'payment_statuses'));
    }

    public function testWhatsApp(Application $application)
    {
        // Pastikan relasi yang dibutuhkan sudah dimuat
        $application->load('prospective.user', 'programChoices.program');

        // Picu notifikasi ke user terkait
        $application->prospective->user->notify(new MahasiswaDiterima($application));

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Percobaan pengiriman notifikasi WhatsApp untuk ' . $application->prospective->user->name . ' telah dijalankan di background.');
    }

    public function testEmail(Application $application)
    {
        // Pastikan relasi yang dibutuhkan sudah dimuat
        $application->load('prospective.user', 'programChoices.program');

        // Picu notifikasi ke user terkait.
        // Class MahasiswaDiterima sudah tahu cara mengirim email.
        $application->prospective->user->notify(new MahasiswaDiterima($application));

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Percobaan pengiriman email untuk ' . $application->prospective->user->name . ' telah dijalankan.');
    }
}