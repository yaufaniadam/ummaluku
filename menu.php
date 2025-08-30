 'menu' => [
        [
            'type' => 'navbar-notification',
            'id' => 'my-notification',
            'icon' => 'fas fa-bell',
            'url' => 'notifications/show',
            'topnav_right' => true,
            'dropdown_mode' => true,
            'dropdown_flabel' => 'Semua Notifikasi',
            'update_cfg' => [
                'url' => 'notifications/get',
                'period' => 30,
            ],
        ],

        // MENU UTAMA
        [
            'text' => 'Dashboard',
            'route'  => 'admin.dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'can'    => ['view pmb'],
        ],
        // MAHASISWA
        [
            'text' => 'Dashboard',
            'url'  => 'mahasiswa/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'role'    => 'Mahasiswa'
        ],
        [
            'text' => 'Pengisian KRS',
            'url'  => 'mahasiswa/krs',
            'icon' => 'fas fa-fw fa-edit',
            'role' => 'Mahasiswa',
        ],

        [
            'text' => 'Dashboard',
            'url'  => 'dosen/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'role' => 'Dosen',
        ],

        [
            'header' => 'BIMBINGAN AKADEMIK',
            'role'   => 'Dosen',
        ],
        [
            'text' => 'Persetujuan KRS',
            'route'  => 'dosen.krs-approval.index',
            'icon' => 'fas fa-fw fa-check-square',
            'role' => 'Dosen',
        ],

        [
            'text' => 'Mahasiswa Bimbingan',
            'route'  => 'dosen.advised-students.index',
            'icon' => 'fas fa-fw fa-users',
            'role' => 'Dosen',
        ],

        [
            'header' => 'PENGAJARAN',
            'role'   => 'Dosen',
        ],
        [
            'text' => 'Input Nilai',
            'route'  => 'dosengrades.input.index',
            'icon' => 'fas fa-fw fa-keyboard',
            'role' => 'Dosen',
        ],

        [
            'header' => 'KEUANGAN',
            // 'can'    => 'manage-keuangan', // Izin bisa diatur nanti
        ],
        [
            'text' => 'Pengaturan Biaya Kuliah',
            'url'  => 'admin/tuition-fees',
            'icon' => 'fas fa-fw fa-money-bill-wave',
        ],
        [
            'text' => 'Komponen Biaya', // <-- MENU BARU
            'url'  => 'admin/fee-components',
            'icon' => 'fas fa-fw fa-tags',
        ],

        // MODUL PENDAFTARAN MAHASISWA BARU
        [
            'text'    => 'PMB',
            'icon'    => 'fas fa-fw fa-user-plus',
            'can' => 'view pmb', // Contoh jika nanti dibatasi oleh permission

            'submenu' => [
                [
                    'text' => 'Data Pendaftar',
                    // Menggunakan array agar menu tetap aktif di halaman index dan show
                    'route'  => ['admin.pendaftaran.index', 'admin.seleksi.index'],
                    'icon' => 'fas fa-fw fa-users',
                    'can' => 'view pmb',
                ],
                [
                    'text' => 'Proses Seleksi', // 
                    'route'  => 'admin.seleksi.index',
                    'icon' => 'fas fa-fw fa-search',
                    'can' => 'view pmb',
                ],
                [
                    'text' => 'Diterima',
                    'route'  => 'admin.diterima.index',
                    'icon' => 'fas fa-fw fa-check',
                    'can' => 'view pmb',
                ],
                [
                    'text' => 'Pembayaran Registrasi',
                    'route'  => 'admin.payment.index',
                    'icon' => 'fas fa-fw fa-cash-register',
                    'can' => 'view pmb',
                ],
            ],
        ],
        [
            'text'    => 'Pengaturan PMB',
            'icon'    => 'fas fa-fw fa-user-plus',
            'can' => 'manage pmb',
            'submenu' => [
                [
                    'text' => 'Gelombang',
                    'route'  => 'admin.gelombang.index', //admin.settings.batch'
                    'icon' => 'fas fa-fw fa-calendar',
                    'can' => 'manage pmb',
                ],
                [
                    'text' => 'Jalur Pendaftaran',
                    'route'  => 'admin.jalur-pendaftaran.index', //admin.settings.category
                    'icon' => 'fas fa-fw fa-folder',
                    'can' => 'manage pmb',
                ],
            ],
        ],

        // MODUL AKADEMIK
        [
            'text'    => 'Akademik',
            'icon'    => 'fas fa-fw fa-graduation-cap',
            'can' => 'view akademik',
            'submenu' => [
                [
                    'text' => 'Mahasiswa',
                    'url'  => 'admin/students',
                    'icon' => 'fas fa-fw fa-user-graduate',
                    'can' => 'view akademik',
                ],

                [
                    'text' => 'Import Mahasiswa Lama',
                    'url'  => 'admin/students/import',
                    'icon' => 'fas fa-fw fa-file-import',
                    'can'  => 'manage settings',
                ],

                [
                    'text' => 'Dosen',
                    'url'  => 'admin/lecturers',
                    'icon' => 'fas fa-fw fa-user-tie',
                    'can'  => 'manage settings',
                ],
            ],
        ],

        // MODUL KEPEGAWAIAN
        [
            'text'    => 'Kepegawaian',
            'icon'    => 'fas fa-fw fa-briefcase',
            'can' => 'view sdm',
            'submenu' => [
                [
                    'text' => 'Data Pegawai & Dosen',
                    'route'  => '',
                    'icon' => 'fas fa-fw fa-id-card',
                    'can' => 'view sdm',
                ],
            ],
        ],

        // PENGATURAN SISTEM (HANYA UNTUK SUPER ADMIN)
        [
            'header' => 'PENGATURAN SISTEM',
            'can'  => 'manage settings', // Hanya bisa dilihat oleh role yg punya permission ini
        ],
        [
            'text'    => 'Pengaturan',
            'icon'    => 'fas fa-fw fa-cogs',
            'can'  => 'manage settings',
            'submenu' => [
                [
                    'text' => 'Manajemen User',
                    'route'  => '',
                    'icon' => 'fas fa-fw fa-users-cog',
                    'can'  => 'manage settings',
                ],
                [
                    'text' => 'Roles & Permissions',
                    'route'  => '',
                    'icon' => 'fas fa-fw fa-key',
                    'can'  => 'manage settings',
                ],
                [
                    'text' => 'Pengaturan Umum',
                    'route'  => '',
                    'icon' => 'fas fa-fw fa-sliders-h',
                    'can'  => 'manage settings',
                ],

                [
                    'text' => 'Kurikulum',
                    'url'  => 'admin/curriculums',
                    'icon' => 'fas fa-fw fa-sitemap',
                    'can'  => 'manage settings',
                ],
                [
                    'text' => 'Tahun Ajaran',
                    'url'  => 'admin/academic-years',
                    'icon' => 'fas fa-fw fa-calendar-alt',
                    'can'  => 'manage settings',
                ],
            ],
        ],
    ],