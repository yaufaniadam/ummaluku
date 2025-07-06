@extends('layouts.frontend')

{{-- Mendefinisikan judul khusus untuk halaman ini --}}
@section('title', 'Universitas Muhammadiyah Maluku')

{{-- Mendefinisikan konten utama halaman --}}
@section('content')

    <!-- Hero Section -->
    <header class="bg-ummaluku-image pt-5 pb-0 ">
        <div class="container px-5">
            <div class="row gx-5 align-items-center justify-content-center">
                <div class="col-lg-8 col-xl-7 col-xxl-6">
                    <div class="my-5 text-center text-xl-start">
                        <h1 class="display-6 fw-bolder text-dark mb-2">Mari Bergabung Bersama Universitas Muhammadiyah Maluku
                        </h1>
                        <p class="lead fw-normal text-dark-60 mb-4">Wujudkan cita-citamu dan jadilah bagian dari generasi
                            unggul, islami, dan berwawasan global.</p>
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                            <a class="btn btn-warning btn-lg px-4 me-sm-3" href="#pendaftaran">Daftar Sekarang</a>
                            <a class="btn btn-outline-warning btn-lg px-4" href="#prodi">Lihat Program Studi</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                    {{-- Ganti dengan gambar kampus Anda --}}
                    <img class="img-fluid rounded-3 mt-5"
                        src="{{ asset('assets/mhs.png')  }}"
                        alt="Kampus Universitas Muhammadiyah Maluku" />
                </div>
            </div>
        </div>
    </header>

    <!-- Section Program Studi (DINAMIS) -->
    <section class="py-5" id="prodi">
        <div class="container px-5 my-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="text-center">
                        <h2 class="fw-bolder">Program Studi Unggulan</h2>
                        <p class="lead fw-normal text-muted mb-5">Pilih bidang yang sesuai dengan minat dan bakat Anda dari
                            berbagai program studi terbaik yang kami tawarkan.</p>
                    </div>
                </div>
            </div>
            <div class="row gx-5">
                {{-- Melakukan perulangan untuk setiap prodi yang dikirim dari controller --}}
                @forelse ($prodis as $prodi)
                    <div class="col-lg-4 mb-5">
                        {{-- Arahkan link ke halaman detail prodi menggunakan slug --}}
                        <a href="" class="text-decoration-none">
                            <div class="card h-100 shadow border-0">
                                <div class="card-body p-4">
                                    {{-- Tampilkan ikon dari database, jika tidak ada, gunakan ikon default --}}
                                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3 mt-n1"><i
                                            class="{{ $prodi->icon_class ?? 'bi bi-book-fill' }}"></i></div>
                                    {{-- Tampilkan nama prodi --}}
                                    <h5 class="card-title mb-3 text-dark">{{ $prodi->name_id }} ({{ $prodi->degree }})</h5>
                                    {{-- Tampilkan deskripsi singkat --}}
                                    <p class="card-text mb-0 text-muted">{{ $prodi->short_description }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    {{-- Pesan ini akan muncul jika tidak ada data prodi di database --}}
                    <div class="col-12">
                        <p class="text-center text-muted">Saat ini belum ada program studi yang tersedia untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Section Jalur Pendaftaran & Beasiswa -->
    <section class="py-5 bg-light" id="pendaftaran">
        <div class="container px-5 my-5">

            <div class="text-center mb-5">
                <h2 class="fw-bolder">Jalur Pendaftaran</h2>
                <p class="lead fw-normal text-muted">Pilih jalur pendaftaran yang paling sesuai untuk Anda.</p>
            </div>
            <div class="row gx-4">
                {{-- Pastikan ada gelombang yang aktif secara umum sebelum menampilkan daftar --}}
                @if ($activeBatch)
                    @forelse ($categories as $category)
                        <div class="col-md-6 col-lg-4 mb-4">
                            {{-- Link pendaftaran sekarang dinamis dan mengarah ke halaman detail --}}
                            <a href="{{ route('pendaftaran.category.detail', $category->slug) }}"
                                class="text-decoration-none">
                                <div
                                    class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm card-link">
                                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                                        {{-- Anda bisa menambahkan logika untuk ikon yang berbeda di sini nanti berdasarkan slug atau nama kategori --}}
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </div>
                                    <h5 class="fw-bolder text-dark">{{ $category->name }}</h5>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Saat ini belum ada jalur pendaftaran yang dibuka.</p>
                        </div>
                    @endforelse
                @else
                    <div class="col-12">
                        <p class="text-center text-muted">Pendaftaran belum dibuka.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="py-5 bg-light" id="beasiswa">
        <div class="container px-5 my-5">

            <div class="text-center mb-5">
                <h2 class="fw-bolder">Pilihan Beasiswa</h2>
                <p class="lead fw-normal text-muted">Raih kesempatan mengenyam pendidikan dengan berbagai program beasiswa.
                </p>
            </div>
            <div class="row gx-4">
                <div class="col-md-6 mb-4">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-award-fill"></i></div>
                            <h5 class="fw-bolder text-dark">Nilai Rapor</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-people-fill"></i></div>
                            <h5 class="fw-bolder text-dark">Kader Unggulan Muhammadiyah (KAUM)</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-book-fill"></i></div>
                            <h5 class="fw-bolder text-dark">Hafizh Muhammadiyah</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-trophy-fill"></i></div>
                            <h5 class="fw-bolder text-dark">Talenta Atlet & Seni</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-4 mx-auto">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-bank"></i></div>
                            <h5 class="fw-bolder text-dark">KIP – Kuliah</h5>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </section>
    <section class="py-5 bg-light" id="kontak">
        <div class="container px-5 my-5">

            <div class="text-center mb-5">
                <h2 class="fw-bolder">Kontak</h2>
                <p class="lead fw-normal text-muted">hubungi Admisi Universitas Muhammadiyah Maluku.
                </p>
            </div>
            <div class="row gx-4">
                <div class="col-12 mb-4">
                    <a href="#!" class="text-decoration-none">
                        <div
                            class="d-flex flex-column align-items-center text-center p-4 border rounded-3 h-100 shadow-sm">
                            <div class="feature bg-success bg-gradient text-white rounded-3 mb-3"><i
                                    class="bi bi-whatsapp"></i></div>
                            <h5 class="fw-bolder text-dark">085612345678</h5>
                        </div>
                    </a>
                </div>
                
            </div>

        </div>
    </section>

@endsection
