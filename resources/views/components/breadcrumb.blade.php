<ul class="nav nav-tabs mb-3">


    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pendaftar') ? 'active' : '' }}" href="{{ route('pendaftar') }}">
            <i class="fa-solid fa-house me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pendaftar.biodata') ? 'active' : '' }}"
            href="{{ route('pendaftar.biodata') }}">
            @if ($current === 'lengkapi_data')
                <i class="bi bi-check-circle text-success"></i>
            @endif

            Data Diri
        </a>
    </li>


    <li class="nav-item">
        @if ($current === 'lengkapi_data')
            {{-- Tab dikunci: data diri belum diisi --}}
            <span class="nav-link disabled text-muted" style="cursor: not-allowed;" title="Lengkapi Data Diri terlebih dahulu">
                <i class="bi bi-lock-fill me-1"></i> Dokumen
            </span>
        @else
            <a class="nav-link {{ request()->routeIs('pendaftar.document.form') ? 'active' : '' }}"
                href="{{ route('pendaftar.document.form') }}">
                @if (in_array($current, ['proses_verifikasi', 'lolos_verifikasi_data', 'diterima', 'sudah_registrasi']))
                    <i class="bi bi-check-circle text-success me-1"></i>
                @endif
                Dokumen
            </a>
        @endif
    </li>

    @if ($current === 'diterima')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pendaftar.registrasi') ? 'active' : '' }}"
                href="{{ route('pendaftar.registrasi') }}">
                <i class="fa-solid fa-house me-2"></i>
                Registrasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="">
                <i class="fa-solid fa-house me-2"></i> Selesai
            </a>
        </li>
    @endif

</ul>
