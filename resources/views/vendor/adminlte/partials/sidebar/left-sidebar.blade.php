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


                    @if (auth()->user()->hasRole(['Super Admin']))
                      
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-lock"></i>
                                <p>Super Admin</p>
                            </a>
                        </li>
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL PRODI (KAPRODI, STAF PRODI) --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole(['Kaprodi', 'Staf Prodi']))
                        <li class="nav-header">PORTAL PRODI</li>

                        <li class="nav-item">
                            <a href="{{ route('prodi.dashboard') }}"
                                class="nav-link {{ request()->routeIs('prodi.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @can('kurikulum-list')
                        <li class="nav-item">
                            <a href="{{ route('prodi.course.index') }}"
                                class="nav-link {{ request()->routeIs('prodi.course.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Manajemen Matakuliah</p>
                            </a>
                        </li>
                        @endcan

                        @can('kelas-list')
                        <li class="nav-item">
                            <a href="{{ route('prodi.course-class.index') }}"
                                class="nav-link {{ request()->routeIs('prodi.course-class.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Manajemen Kelas</p>
                            </a>
                        </li>
                        @endcan

                        @role('Kaprodi')
                        <li class="nav-item">
                            <a href="{{ route('prodi.krs-approval.index') }}"
                                class="nav-link {{ request()->routeIs('prodi.krs-approval.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-check-double"></i>
                                <p>Persetujuan KRS</p>
                            </a>
                        </li>
                        @endrole
                    @endif

                    @if (auth()->user()->hasRole('Super Admin'))
                        <li class="nav-header">SYSTEM</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.system.roles.index') }}"
                                class="nav-link {{ request()->routeIs('admin.system.roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Manajemen Role</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.system.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.system.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK EKSEKUTIF (RECTOR, VICE RECTOR, ETC)   --}}
                    {{-- ================================================= --}}
                    @can('view-executive-dashboard')
                        <li class="nav-header">EKSEKUTIF</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.executive.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.executive.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Executive Dashboard</p>
                            </a>
                        </li>
                    @endcan

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL ADMISI (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Admisi', 'Staf Admisi']))
                        <li class="nav-header">PMB</li>
                        @can('view pmb dashboard')
                            <li class="nav-item">
                                <a href="{{ route('admin.pmb.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('admin.pmb.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        @endcan

                        @can('view applications')
                            @php
                                $PMB = [
                                    'admin.pmb.pendaftaran.index',
                                    'admin.pmb.seleksi.index',
                                    'admin.pmb.diterima.index',
                                    'admin.pmb.payment.index',
                                ];
                            @endphp

                            <li class="nav-item {{ request()->routeIs($PMB) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($PMB) ? 'active' : '' }}">
                                    <i class="nav-icon fas fas fa-edit"></i>
                                    <p>
                                        Administrasi PMB
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.pmb.pendaftaran.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.pmb.pendaftaran.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Pendaftar</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.pmb.seleksi.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.pmb.seleksi.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Proses Seleksi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.pmb.diterima.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.diterima.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Diterima</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.pmb.payment.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.pmb.payment.index') ? 'active' : '' }}">
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
                                    'admin.pmb.gelombang.index', // Route untuk gelombang
                                    'admin.pmb.jalur-pendaftaran.index', // Contoh route lain yang namanya tidak berurutan
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
                                            <a href="{{ route('admin.pmb.gelombang.index') }}"
                                                class="nav-link {{ request()->routeIs('admin.pmb.gelombang.index') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Manajemen Gelombang</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.pmb.jalur-pendaftaran.index') }}"
                                                class="nav-link {{ request()->routeIs('admin.pmg.jalur-pendaftaran.index') ? 'active' : '' }}">
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
                    {{-- MENU MASTER DATA (SUPER ADMIN/ADMIN) --}}
                    {{-- ================================================= --}}
                    @if (auth()->user()->hasRole(['Super Admin', 'Admin']))
                        <li class="nav-header">MASTER DATA</li>
                        <li class="nav-item">
                            <a href="{{ route('master.work-units.index') }}"
                                class="nav-link {{ request()->routeIs('master.work-units.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Unit Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.programs.index') }}"
                                class="nav-link {{ request()->routeIs('master.programs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-university"></i>
                                <p>Program Studi</p>
                            </a>
                        </li>
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL AKADEMIK (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}

                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Akademik', 'Staf Akademik']))
                        <li class="nav-header">AKADEMIK</li>
                        @can('mahasiswa-list')
                            <li class="nav-item">
                                <a href="{{ route('admin.akademik.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('admin.akademik.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        @endcan

                        @can('mahasiswa-list')
                            @php
                                $mahasiswa = [
                                    'admin.akademik.students.index',
                                    'admin.akademik.students.import',
                                    'admin.akademik.students.import.form',
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
                                        <a href="{{ route('admin.akademik.students.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.akademik.students.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Mahasiswa</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.akademik.students.import.form') }}"
                                            class="nav-link {{ request()->routeIs('admin.akademik.students.import.form') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Import Mahasiswa Lama</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endcan




                        @can('mahasiswa-list')
                            @php
                                $kurikulum = ['admin.akademik.curriculums.*', 'admin.akademik.curriculums.courses.*'];
                            @endphp
                            <li class="nav-item">
                                <a href="{{ route('admin.akademik.curriculums.index') }}"
                                    class="nav-link {{ request()->routeIs($kurikulum) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-folder"></i>
                                    <p>Kurikulum</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.akademik.courses.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.akademik.courses.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Master Mata Kuliah</p>
                                </a>
                            </li>
                        @endcan

                        @can('mahasiswa-list')
                            @php
                                $akademik = [
                                    'admin.akademik.academic-years.index',
                                    'admin.akademik.academic-years.show',
                                    'admin.akademik.academic-years.programs.*',
                                    'admin.akademik.academic-events.index',
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
                                        <a href="{{ route('admin.akademik.academic-years.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.akademik.academic-years.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Tahun Ajaran</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.akademik.academic-events.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.akademik.academic-events.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Kalender Akademik</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                        @endcan
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL SDM (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}

                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Akademik', 'Staf Akademik']))
                        <li class="nav-header">SDM</li>
                        @can('dosen-list')
                            <li class="nav-item">
                                <a href="{{ route('admin.sdm.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('admin.sdm.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        @endcan


                        @can('dosen-list')
                            @php
                                $dosen = ['admin.sdm.lecturers.index', 'admin.sdm.lecturers.create'];
                            @endphp

                            <li class="nav-item {{ request()->routeIs($dosen) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($dosen) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>
                                        Dosen
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.sdm.lecturers.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.sdm.lecturers.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Data Dosen</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.sdm.lecturers.create') }}"
                                            class="nav-link {{ request()->routeIs('admin.sdm.lecturers.create') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Tambah Dosen</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>
                                        Tenaga Kependidikan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.sdm.staff.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.sdm.staff.index') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Data Tendik</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.sdm.staff.create') }}"
                                            class="nav-link {{ request()->routeIs('admin.sdm.staff.create') ? 'active' : '' }}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Tambah Tendik</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                        @endcan
                    @endif

                    {{-- ================================================= --}}
                    {{-- MENU UNTUK PORTAL KEUANGAN (STAF, DIREKTUR, SUPER ADMIN) --}}
                    {{-- ================================================= --}}

                    @if (auth()->user()->hasRole(['Super Admin', 'Direktur Keuangan', 'Staf Keuangan']))
                        <li class="nav-header">KEUANGAN</li>
                        @can('biaya-list')
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.tuition-fees.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.tuition-fees.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>Biaya Kuliah</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.payment-verification.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.payment-verification.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>Verifikasi Pembayaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.keuangan.fee-components.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.keuangan.fee-components.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Komponen Biaya</p>
                                </a>
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

                        @can('mahasiswa-krs-fill')
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.dashboard') }}"
                                    class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>Beranda</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.krs.index') }}"
                                    class="nav-link {{ request()->routeIs('mahasiswa.krs.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>KRS</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.hasil-studi.index') }}"
                                    class="nav-link {{ request()->routeIs('mahasiswa.krasil-studi.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Kartu Hasil Studi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.profil.index') }}"
                                    class="nav-link {{ request()->routeIs('mahasiswa.profil.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-id-card"></i>
                                    <p>Lengkapi Biodata</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.keuangan.index') }}"
                                    class="nav-link {{ request()->routeIs('mahasiswa.keuangan.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>Info Tagihan</p>
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

                    <li class="nav-header">LAIN-LAIN</li>
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}"
                            class="nav-link {{ request()->routeIs('calendar.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Kalender Akademik</p>
                        </a>
                    </li>


                @endauth
                {{-- AKHIR DARI LOGIKA MENU MANUAL KITA --}}

            </ul>
        </nav>

        @if (auth()->user()->hasRole('Camaru'))
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
