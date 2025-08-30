<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'UM Maluku',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '',
    'logo_img' => 'assets/logo-orange.png',
    'logo_img_class' => 'w-100',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => '/assets/logo-orange.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 300,
            'height' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-warning',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-warning',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-warning',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-warning elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => '',
    'password_reset_url' => '',
    'password_email_url' => '',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

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
            // 'route'  => 'dosengrades.input.index',
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

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        //   App\Filters\MenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => true,
];
