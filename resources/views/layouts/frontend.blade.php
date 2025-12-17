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
        .steps {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            position: relative;
            padding-bottom: 1.5rem;
        }

        .step-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 12px;
            /* Pusatkan dengan step-marker */
            top: 24px;
            width: 2px;
            height: 100%;
            background-color: #e9ecef;
        }

        .step-marker {
            min-width: 26px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #e9ecef;
            border: 2px solid #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            color: #fff;
            font-weight: bold;
        }

        .step-details {
            margin-left: 1rem;
            padding-top: 2px;
        }

        .step-title {
            font-weight: 500;
            color: #6c757d;
            /* Warna untuk pending */
        }

        /* Status: Completed */
        .step-item.completed .step-marker {
            background-color: #198754;
            /* Warna success Bootstrap */
            border-color: #198754;
        }

        .step-item.completed .step-marker::before {
            content: '\2713';
            /* Simbol centang */
            font-size: 14px;
        }

        .step-item.completed .step-title {
            color: #212529;
        }

        /* Status: Active */
        .step-item.active .step-marker {
            background-color: #fff;
            border-color: #0d6efd;
            /* Warna primary Bootstrap */
        }

        .step-item.active .step-title {
            font-weight: bold;
            color: #0d6efd;
        }

        /* Status: Active-Danger (untuk Ditolak) */
        .step-item.active-danger .step-marker {
            background-color: #dc3545;
            /* Warna danger Bootstrap */
            border-color: #dc3545;
        }

        .step-item.active-danger .step-marker::before {
            content: '\2717';
            /* Simbol silang */
            font-size: 16px;
        }

        .step-item.active-danger .step-title {
            font-weight: bold;
            color: #dc3545;
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
            background-color: #0193de;
        }
        .bg-ummaluku-orange {
            background-color: #f76603;
        }
        .bg-ummaluku-light-80 {
            background-color:rgba(255, 255, 255, 0.8);
        }
        .bg-ummaluku-image{
           background-image:url('{{ asset('assets/bg.jpg') }}');
           background-position: left center;
        }
        .h-600 {
            height:600px;
        }

    </style>



    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-ummaluku-light-80">
            <div class="container px-5">
                <a class="navbar-brand" href="{{ route('gateway') }}"><img src="{{ asset('assets/logo-orange.png') }}"
                        width="270" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span></button>


                {{-- Di dalam file layouts/frontend.blade.php --}}

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                     
                        @auth
                            {{-- MENU INI HANYA MUNCUL JIKA USER SUDAH LOGIN --}}
                            @php
                                $dashboardRoute = '#';
                                $user = Auth::user();
                                if ($user->hasRole(['Super Admin', 'Direktur Admisi', 'Staf Admisi', 'Direktur SDM', 'Staf SDM', 'Direktur Keuangan', 'Staf Keuangan', 'Kaprodi', 'Staf Prodi'])) {
                                    $dashboardRoute = route('admin.dashboard');
                                } elseif ($user->hasRole('Dosen')) {
                                    $dashboardRoute = route('dosen.dashboard');
                                } elseif ($user->hasRole('Mahasiswa')) {
                                    $dashboardRoute = route('mahasiswa.dashboard');
                                } elseif ($user->hasRole('Camaru')) {
                                    $dashboardRoute = route('pendaftar');
                                } elseif ($user->staff) {
                                     // Fallback for staff without specific admin roles (Tendik)
                                     $dashboardRoute = route('staff.dashboard'); // Assuming this route exists based on context
                                }
                            @endphp
                            <li class="nav-item"><a class="nav-link" href="{{ $dashboardRoute }}">Dashboard
                                    Saya</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- <li><a class="dropdown-item" href="#!">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="#!">Ganti Password</a></li> --}}
                                        {{-- <li>
                                            <hr class="dropdown-divider" />
                                        </li> --}}
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
                            {{-- @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif --}}
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
