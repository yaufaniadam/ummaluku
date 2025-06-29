@extends('layouts.frontend')

@section('title', 'Pendaftaran Berhasil')

@section('content')

    @if (session('registration_data'))
        @php
            $data = session('registration_data');
        @endphp
        {{-- Header Halaman Dinamis --}}
        <div class="page-header d-print-none pt-5 pb-5 bg-light">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            {{ $data['category_name'] }}
                        </h2>
                        <div class="text-muted mt-1">
                            {{ $data['batch_name'] }} - Tahun Ajaran {{ $data['batch_year'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container py-4">       

        <div class="text-center">
            <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check text-success"
                    width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                    <path d="M9 12l2 2l4 -4"></path>
                </svg>
            </div>
            <h1 class="h1">Pendaftaran Berhasil!</h1>
        </div>

        @if (session('registration_data'))
            <div class="card card-body my-4">
                <h3 class="card-title">Data Login Anda</h3>
                <p>Silakan gunakan informasi di bawah ini untuk login ke Portal Pendaftar dan melengkapi dokumen Anda.</p>
                <dl class="row">
                    <dt class="col-3">Email:</dt>
                    <dd class="col-9"><strong>{{ $data['email'] }}</strong></dd>
                    <dt class="col-3">Password:</dt>
                    <dd class="col-9"><strong>{{ $data['password'] }}</strong></dd>
                </dl>
                <div class="alert alert-danger">
                    <strong>PENTING!</strong> Harap catat dan simpan password Anda di tempat yang aman. Password ini hanya
                    ditampilkan satu kali.
                </div>
            </div>
        @endif

        <div class="text-center text-muted mt-3">
            Notifikasi berisi data login juga telah kami kirimkan ke alamat email Anda.
            <br>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Lanjut ke Halaman Login</a>
        </div>
    </div>

@endsection
