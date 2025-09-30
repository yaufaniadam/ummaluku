@extends('adminlte::page')

@section('title', 'Kelola Isi Kurikulum')

@section('content_header')
    <h1 class="mb-1">Kelola Isi Kurikulum</h1>
    <h5 class="font-weight-light">
        <a href="{{ route('admin.akademik.curriculums.index') }}" wire:navigate>Manajemen Kurikulum</a> >
        {{ $curriculum->name }}
    </h5>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Tombol aksi sekarang berada di luar form --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Mata Kuliah dalam Kurikulum</h3>
            <div class="card-tools">
               @if (auth()->user()->hasRole(['Super Admin']))
                <a href="{{ route('admin.akademik.curriculums.courses.add', $curriculum->id) }}"
                    class="btn btn-primary btn-sm" wire:navigate>
                    <i class="fas fa-plus"></i> Tambah MK dari Master
                </a>
                @endif
                
            </div>
        </div>

        {{-- Form untuk bulk delete sekarang HANYA membungkus tabel --}}
        {{-- <form action="{{ route('admin.akademik.curriculums.courses.bulkDestroy', $curriculum->id) }}" method="POST" id="bulk-delete-form">
            @csrf
            @method('DELETE') --}}
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px;"><input type="checkbox" id="checkAll"></th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Sifat</th>
                        @if (auth()->user()->hasRole(['Super Admin']))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    {{-- Lakukan perulangan untuk setiap grup semester --}}
                    @forelse ($coursesBySemester as $semester => $courses)
                        {{-- Tampilkan baris sub-judul semester --}}
                        <tr class="bg-light">
                            <td colspan="6" class="font-weight-bold">
                                @if ($semester > 0)
                                    SEMESTER {{ $semester }}
                                @else
                                    MATA KULIAH BELUM DIATUR SEMESTERNYA
                                @endif
                            </td>
                        </tr>

                        {{-- Lakukan perulangan untuk setiap mata kuliah di dalam grup semester tersebut --}}
                        @foreach ($courses as $course)
                            <tr>
                                <td>{{-- Checkbox untuk bulk-delete --}}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->sks }}</td>
                                <td>
                                    <span
                                        class="badge {{ $course->pivot->type === 'Wajib' ? 'badge-danger' : 'badge-info' }}">
                                        {{ $course->pivot->type }}
                                    </span>
                                </td>
                                 @if (auth()->user()->hasRole(['Super Admin']))
                                <td>
                                    {{-- Form untuk single delete sekarang independen dan tidak bersarang --}}
                                    <form
                                        action="{{ route('admin.akademik.curriculums.courses.destroy', ['curriculum' => $curriculum->id, 'course' => $course->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari kurikulum?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada mata kuliah di dalam kurikulum ini.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        {{-- </form> --}}
    </div>
@stop

@push('js')
    <script>
        // Skrip untuk fungsionalitas checkbox "Pilih Semua"
        document.getElementById('checkAll').addEventListener('click', function(event) {
            document.querySelectorAll('.course-checkbox').forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
            toggleBulkDeleteButton();
        });

        // Skrip untuk menampilkan/menyembunyikan tombol hapus massal
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const allCheckboxes = document.querySelectorAll('.course-checkbox');

        function toggleBulkDeleteButton() {
            const oneIsChecked = Array.from(allCheckboxes).some(checkbox => checkbox.checked);
            bulkDeleteBtn.style.display = oneIsChecked ? 'inline-block' : 'none';
        }

        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleBulkDeleteButton);
        });

        // JavaScript sekarang bertanggung jawab untuk men-submit form bulk delete
        // document.getElementById('bulk-delete-btn').addEventListener('click', function() {
        //     if (confirm('Apakah Anda yakin ingin menghapus semua mata kuliah yang dipilih dari kurikulum ini?')) {
        //         document.getElementById('bulk-delete-form').submit();
        //     }
        // });
    </script>
@endpush
