<?php

namespace App\Services\Dashboard;

use App\Models\Lecturer;
use App\Models\User; // Tendik mungkin belum punya model sendiri, kita bisa hitung dari User dengan role Tendik

class SDMDashboardService
{
    public function getDashboardData()
    {
        $jumlahDosenAktif = Lecturer::where('status', 'active')->count();

        // Asumsi Tendik adalah User dengan peran 'Tendik'
        $jumlahTendikAktif = User::whereHas('roles', function ($query) {
            $query->where('name', 'Tendik');
        })->count(); // Anda bisa tambahkan filter status aktif jika ada

        // Data untuk grafik bisa ditambahkan di sini nanti
        // Misal: jumlah dosen per jabatan fungsional

        return [
            'jumlahDosenAktif' => $jumlahDosenAktif,
            'jumlahTendikAktif' => $jumlahTendikAktif,
        ];
    }
}