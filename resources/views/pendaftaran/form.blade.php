@extends('layouts.frontend')

@section('content')
    <div class="page-header d-print-none pt-5 pb-5 bg-light">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    {{-- Judul Halaman Dinamis --}}
                    <h2 class="page-title">
                        {{ $category->name }}
                    </h2>
                    <div class="text-muted mt-1">
                        {{ $batch->name }} - Tahun Ajaran {{ $batch->year }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container py-4">

            <x-stepper current-step="1" step1-text="Pendaftaran Awal" step2-text="Lengkapi Biodata" step3-text="Upload Dokumen"
            step4-text="Selesai" />

            {{-- Meneruskan slug dan id ke komponen Livewire seperti sebelumnya --}}
            @livewire('pendaftaran.form-pendaftaran', [
                'categorySlug' => $category->slug,
                'batchId' => $batch->id,
            ])
        </div>
    </div>
@endsection
