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
                    {{-- MENU UNTUK PORTAL ADMISI (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Admisi', 'Staf Admisi']))
                        <li class="nav-header">ADMISI</li>
                        @can('view pmb dashboard')
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard.admisi') }}"
                                    class="nav-link {{ request()->routeIs('admin.dashboard.admisi') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard Admisi</p>
                                </a>
                            </li>
                        @endcan

                        @can('view applications')
                            @php
                                $PMB = [
                                    'admin.pendaftaran.index',
                                    'admin.seleksi.index',
                                    'admin.diterima.index',
                                    'admin.payment.index',
                                ];
                            @endphp
                            
                            <li class="nav-item {{ request()->routeIs($PMB) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($PMB) ? 'active' : '' }}">
                                    <i class="nav-icon fas fas fa-edit"></i>
                                    <p>
                                        PMB
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.pendaftaran.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.pendaftaran.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Pendaftar</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.seleksi.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.seleksi.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Proses Seleksi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.diterima.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.diterima.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Diterima</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.payment.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.payment.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Pembayaran Registrasi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('manage pmb settings')
                            @php
                                $pengaturanPMB = [
                                    'admin.gelombang.index', // Route untuk gelombang
                                    'admin.jalur-pendaftaran.index', // Contoh route lain yang namanya tidak berurutan
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
                    {{-- MENU UNTUK PORTAL AKADEMIK (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}

                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Akademik', 'Staf Akademik']))
                    <li class="nav-header">AKADEMIK</li>
                        @can('dosen-list' )
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard.akademik') }}"
                                    class="nav-link {{ request()->routeIs('admin.dashboard.akademik') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard Akademik</p>
                                </a>
                            </li>
                        @endcan

                        @can('mahasiswa-list')
                            @php
                                $mahasiswa = [
                                    'admin.students.index',
                                    'admin.students.import',
                                    'admin.students.import.form'
                                ];
                            @endphp
                            
                            <li class="nav-item {{ request()->routeIs($mahasiswa) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($mahasiswa) ? 'active' : '' }}">
                                    <i class="nav-icon fas fas fa-users"></i>
                                    <p>
                                        Mahasiswa
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.students.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Mahasiswa</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.students.import.form') }}"
                                            class="nav-link {{ request()->routeIs('admin.students.import.form') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Import Mahasiswa Lama</p>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                        @endcan

                        @can('dosen-list')
                            @php
                                $dosen = [
                                    'admin.lecturers.index',
                                    'admin.lecturers.import',
                                ];
                            @endphp
                            
                            <li class="nav-item {{ request()->routeIs($dosen) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($dosen) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-graduation-cap"></i>
                                    <p>
                                        Dosen
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.lecturers.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.lecturers.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Data Dosen</p>
                                        </a>
                                    </li>
                                   
                                    
                                </ul>
                            </li>
                        @endcan

                        @can('dosen-list')
                            @php
                                $akademik = [
                                    'admin.curriculums.*',
                                    'admin.curriculums.courses.*',
                                    'admin.academic-years.index',
                                    'admin.academic-years.show',
                                    'admin.academic-years.programs.*',
                                ];
                            @endphp
                            
                            <li class="nav-item {{ request()->routeIs($akademik) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($akademik) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Pengaturan Akademik
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.curriculums.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.curriculums.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Kurikulum</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.academic-years.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.academic-years.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Tahun Ajaran</p>
                                        </a>
                                    </li>
                                   
                                    
                                </ul>
                            </li>
                        @endcan

                     
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL DOSEN --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole('Dosen'))
                        <li class="nav-header">PORTAL DOSEN</li>

                  
                            <li class="nav-item">
                                <a href="{{ route('dosen.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="{{ route('dosen.advised-students.index') }}"
                                    class="nav-link {{ request()->routeIs('dosen.advised-students.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas  fa-users"></i>
                                    <p>Mahasiswa Bimbingan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dosen.krs-approval.index') }}"
                                    class="nav-link {{ request()->routeIs('dosen.krs-approval.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check-square"></i>
                                    <p>Persetujuan KRS</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href=""
                                    class="nav-link">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>Jadwal Perkuliahan</p>
                                </a>
                            </li> --}}
                           
                            <li class="nav-item">
                                <a href="{{ route('dosen.grades.input.index') }}"
                                    class="nav-link {{ request()->routeIs('dosen.grades.input.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-edit"></i>
                                    <p>Input Nilai</p>
                                </a>
                            </li>

                             <li class="nav-item">
                                <a href="{{ route('calendar.index') }}"
                                    class="nav-link {{ request()->routeIs('calendar.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>Kalender Akademik</p>
                                </a>
                            </li>
                           
                      

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

        @if (auth()->user()->hasRole('Camaru') )
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
         @endif
    </div>

</aside>
