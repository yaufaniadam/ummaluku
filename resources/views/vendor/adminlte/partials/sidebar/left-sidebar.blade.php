<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>

                {{-- AWAL DARI LOGIKA MENU MANUAL KITA --}}
                @auth

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL ADMIN (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Admisi', 'Staf Admisi']))
                        @can('view pmb dashboard')
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        @endcan

                        @can('view applications')
                            <li class="nav-header">ADMISI</li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pendaftaran.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.pendaftaran.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-edit"></i>
                                    <p>Data Pendaftar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.seleksi.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.seleksi.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-search"></i>
                                    <p>Proses Seleksi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.diterima.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.diterima.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check-circle"></i>
                                    <p>Diterima</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.payment.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.payment.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cash-register"></i>
                                    <p>Pembayaran Registrasi</p>
                                </a>
                            </li>
                        @endcan

                        @can('manage pmb settings')
                        @php
                            $pengaturanPMB = [
                                'admin.gelombang.index', // Route untuk gelombang
                                'admin.jalur-pendaftaran.index' // Contoh route lain yang namanya tidak berurutan
                            ];
                        @endphp
                            <li class="nav-item {{ request()->routeIs($pengaturanPMB) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($pengaturanPMB) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        Pengaturan PMB
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('manage batches')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.gelombang.index') }}"
                                                class="nav-link {{ request()->routeIs('admin.gelombang.index') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Manajemen Gelombang</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.jalur-pendaftaran.index') }}"
                                                class="nav-link {{ request()->routeIs('admin.jalur-pendaftaran.index') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Jalur Pendaftaran</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    @endif


                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL MAHASISWA --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole('Camaru') || auth()->user()->hasRole('Mahasiswa'))
                        <li class="nav-header">PORTAL MAHASISWA</li>

                        @can('access applicant portal')
                            <li class="nav-item">
                                <a href="#" class="nav-link"> {{-- TODO: Ganti href --}}
                                    <i class="nav-icon fas fa-id-card"></i>
                                    <p>Lengkapi Biodata</p>
                                </a>
                            </li>
                        @endcan

                        {{-- Contoh untuk nanti jika sudah jadi mahasiswa --}}
                        {{-- @can('fill krs')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Isi KRS</p>
                            </a>
                        </li>
                        @endcan --}}
                    @endif


                @endauth
                {{-- AKHIR DARI LOGIKA MENU MANUAL KITA --}}

            </ul>
        </nav>

        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>
