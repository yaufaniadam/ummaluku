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
        <a class="nav-link {{ request()->routeIs('pendaftar.document.form') ? 'active' : '' }}"
            href="{{ route('pendaftar.document.form') }}">
            @if ($current === 'upload_dokumen')
                <i class="bi bi-check-circle text-success"></i>
            @endif
            Dokumen
        </a>
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
