@extends('layouts.frontend')

@section('content')
<div class="page-header d-print-none pt-5 pb-5 bg-light">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pendaftaran Baru (Jalur Lain/Gelombang Baru)
                </h2>
                <div class="text-muted mt-1">
                    Silakan pilih Program Studi yang Anda minati.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pendaftar.reapply.store') }}" method="POST">
                    @csrf

                    {{-- Hidden fields for Category and Batch (Assuming we use active ones or passed parameters) --}}
                    {{-- For simplicity, let's let the controller pick the active batch/category or add inputs here if needed.
                         However, usually re-applying implies picking a program.
                         Let's assume the default active batch and category for now, or require them.
                    --}}

                    <div class="mb-3">
                        <label class="form-label required">Pilihan Program Studi 1</label>
                        <select name="program_choice_1" class="form-select @error('program_choice_1') is-invalid @enderror" required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($programs as $facultyName => $facultyPrograms)
                                <optgroup label="{{ $facultyName }}">
                                    @foreach($facultyPrograms as $program)
                                        <option value="{{ $program->id }}">{{ $program->name_id }} ({{ $program->degree }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('program_choice_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilihan Program Studi 2 (Opsional)</label>
                        <select name="program_choice_2" class="form-select @error('program_choice_2') is-invalid @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($programs as $facultyName => $facultyPrograms)
                                <optgroup label="{{ $facultyName }}">
                                    @foreach($facultyPrograms as $program)
                                        <option value="{{ $program->id }}">{{ $program->name_id }} ({{ $program->degree }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('program_choice_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('pendaftar') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Buat Pendaftaran Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
