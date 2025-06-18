<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Pendaftar
                    </h2>
                    <div class="text-muted mt-1">{{ $application->registration_number }}</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('admin.pendaftaran.index') }}" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l14 0"></path><path d="M5 12l4 4"></path><path d="M5 12l-4 -4"></path></svg>
                            Kembali
                        </a>
                        <a href="#" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 12l5 5l10 -10"></path></svg>
                            Verifikasi Pendaftaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biodata Lengkap</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-5">Nama Lengkap:</dt>
                                <dd class="col-7">{{ $application->prospective->user->name }}</dd>
                                <dt class="col-5">Email:</dt>
                                <dd class="col-7">{{ $application->prospective->user->email }}</dd>
                                <dt class="col-5">No. Telepon:</dt>
                                <dd class="col-7">{{ $application->prospective->phone }}</dd>
                                <dt class="col-5">NIK:</dt>
                                <dd class="col-7">{{ $application->prospective->id_number }}</dd>
                                <dt class="col-5">NISN:</dt>
                                <dd class="col-7">{{ $application->prospective->nisn }}</dd>
                                <dt class="col-5">Jenis Kelamin:</dt>
                                <dd class="col-7">{{ $application->prospective->gender }}</dd>
                                <dt class="col-5">Tempat, Tanggal Lahir:</dt>
                                <dd class="col-7">{{ $application->prospective->birth_place }}, {{ \Carbon\Carbon::parse($application->prospective->birth_date)->format('d F Y') }}</dd>
                                <dt class="col-5">Agama:</dt>
                                <dd class="col-7">{{ $application->prospective->religion->name ?? '-' }}</dd>
                                <dt class="col-5">Alamat:</dt>
                                <dd class="col-7">{{ $application->prospective->address }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Data Orang Tua / Wali</h3>
                        </div>
                        <div class="card-body">
                            {{-- Tampilkan data orang tua & wali di sini --}}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pendaftaran</h3>
                        </div>
                        <div class="card-body">
                           <dl class="row">
                                <dt class="col-5">Status:</dt>
                                <dd class="col-7"><span class="badge bg-warning me-1"></span> {{ Str::title(str_replace('_', ' ', $application->status)) }}</dd>
                                <dt class="col-5">Jalur:</dt>
                                <dd class="col-7">{{ $application->admissionCategory->name }}</dd>
                                <dt class="col-5">Gelombang:</dt>
                                <dd class="col-7">{{ $application->batch->name }} ({{$application->batch->year}})</dd>
                                <dt class="col-5">Tanggal Daftar:</dt>
                                <dd class="col-7">{{ $application->created_at->format('d M Y, H:i') }}</dd>
                           </dl>
                           <hr>
                           <h4 class="mb-3">Pilihan Program Studi</h4>
                           @foreach($application->programChoices as $choice)
                           <p>
                               <strong>Pilihan {{ $choice->choice_order }}:</strong><br>
                               {{ $choice->program->name_id }} ({{ $choice->program->degree }})
                           </p>
                           @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>