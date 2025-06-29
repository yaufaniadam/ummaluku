<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    {{-- Judul halaman dinamis dengan nilai default menggunakan @yield --}}
    <title>@yield('title', 'Admisi UM Maluku')</title>

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        /* style.css */

        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            margin-bottom: 2rem;
            /* Memberi jarak ke konten di bawahnya */
        }

        .step-item {
            position: relative;
            display: flex;
            flex-direction: column;
            /* Membuat counter dan nama tersusun vertikal */
            align-items: center;
            /* Menengahkan secara horizontal */
            flex: 1;
            /* Memastikan setiap item mengambil ruang yang sama */
        }

        /* Garis Penghubung */
        .step-item::after {
            content: '';
            position: absolute;
            top: 20px;
            /* Setengah dari tinggi .step-counter (40px / 2) */
            left: 50%;
            width: 100%;
            height: 2px;
            /* Ketebalan garis */
            background-color: #e0e0e0;
            /* Warna garis default */
            z-index: 1;
            /* Diletakkan di belakang counter */
        }

        /* Sembunyikan garis untuk item terakhir */
        .step-item:last-child::after {
            display: none;
        }

        /* Lingkaran Angka */
        .step-counter {
            width: 40px;
            height: 40px;
            border: 2px solid #e0e0e0;
            /* Border default */
            background-color: #ffffff;
            color: #6c757d;
            /* Warna angka default */
            z-index: 2;
            /* Diletakkan di depan garis */
            position: relative;
            /* Diperlukan agar z-index berfungsi */
        }

        /* Judul Langkah */
        .step-name {
            margin-top: 0.5rem;
            color: #6c757d;
        }

        /*
 * ----- PENGATURAN STATUS -----
 */

        /* Status: Selesai (Completed) */
        .step-item.completed .step-counter {
            background-color: var(--bs-primary);
            /* Menggunakan warna primary Bootstrap */
            border-color: var(--bs-primary);
            color: #ffffff;
        }

        .step-item.completed::after {
            background-color: var(--bs-primary);
            /* Garis juga berubah warna */
        }

        .step-item.completed .step-name {
            color: #212529;
            /* Warna teks menjadi lebih jelas */
        }

        /* Status: Aktif (Active) */
        .step-item.active .step-counter {
            border-color: var(--bs-primary);
            color: var(--bs-primary);
            font-weight: bold;
        }

        .step-item.active .step-name {
            color: var(--bs-primary);
            font-weight: bold;
        }

        .bg-ummaluku {
            background-color:#38b6ff;
        }
    </style>



    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-ummaluku">
            <div class="container px-5">             
                 <a class="navbar-brand" href="#"><img src="{{ asset('assets/logoummaluku.png') }}" width="" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>


                {{-- Di dalam file layouts/frontend.blade.php --}}

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pendaftaran">Pendaftaran</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Biaya Kuliah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Registrasi</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Beasiswa</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Kontak</a></li>

                        @auth
                            {{-- MENU INI HANYA MUNCUL JIKA USER SUDAH LOGIN --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('pendaftar.dashboard') }}">Dashboard
                                    Saya</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#!">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="#!">Ganti Password</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        {{-- Tombol Logout harus menggunakan form --}}
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                Logout
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            {{-- MENU INI HANYA MUNCUL JIKA PENGUNJUNG ADALAH TAMU (BELUM LOGIN) --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>



        @yield('content')

    </main>
    <!-- Footer-->
    <footer class="bg-dark py-4 mt-auto">
        <div class="container px-5">
            <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                <div class="col-auto">
                    <div class="small m-0 text-white">Copyright &copy; UM Maluku 2025</div>
                </div>
                <div class="col-auto">
                    <a class="link-light small" href="#!">Privacy</a>
                    <span class="text-white mx-1">&middot;</span>
                    <a class="link-light small" href="#!">Terms</a>
                    <span class="text-white mx-1">&middot;</span>
                    <a class="link-light small" href="#!">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core theme JS-->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts
    @stack('js')
</body>

</html>
