@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Formulir Pendaftaran Mahasiswa Baru
                    </h2>
                    <div class="text-muted mt-1">
                        Silakan lengkapi semua data yang diperlukan di bawah ini.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            {{-- 
                Di sinilah kita memanggil komponen Livewire.
                Kita juga meneruskan variabel $categorySlug dan $batchId dari Controller
                ke dalam komponen Livewire.
            --}}
            @livewire('pendaftaran.form-pendaftaran', [
                'categorySlug' => $categorySlug, 
                'batchId' => $batchId
            ])
        </div>
    </div>
@endsection