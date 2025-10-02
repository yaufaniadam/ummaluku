<?php

namespace App\DataTables;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Student $student) {
                $editUrl = route('admin.akademik.students.edit', $student->id);
                // Tambahkan tombol detail di sini juga
                $detailUrl = route('admin.akademik.students.show', $student->id);
                return '<a href="' . $detailUrl . '" class="btn btn-info btn-sm" wire:navigate>Detail</a> ' .
                    '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';
            })
            ->editColumn('user.name', function (Student $student) {
                return $student->user->name ?? '-';
            })
            ->editColumn('program.name_id', function (Student $student) {
                return $student->program->name_id ?? '-';
            })
            ->addColumn('academic_advisor', function (Student $student) {
                // Tampilkan nama Dosen PA jika ada
                return $student->academicAdvisor->full_name_with_degree ?? '<span class="text-muted">Belum diatur</span>'; //salahnya di sini, selalu memunculkan
            })
            ->rawColumns(['action', 'academic_advisor'])
            ->setRowId('id');
    }

    public function query(Student $model): QueryBuilder
    {
        // Eager load relasi untuk performa
        return $model->newQuery()->with(['user', 'program', 'academicAdvisor'])
            // --- TAMBAHKAN BLOK FILTER INI ---
            ->when($this->request()->get('program_id'), function ($query, $programId) {
                return $query->where('program_id', $programId);
            })
            ->when($this->request()->get('entry_year'), function ($query, $entryYear) {
                return $query->where('entry_year', $entryYear);
            })
            ->when($this->request()->get('status'), function ($query, $status) {
                return $query->where('status', $status);
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('student-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.akademik.students.data'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Student ID')->visible(true),
            Column::make('nim')->title('NIM'),
            Column::make('user.name')->title('Nama Mahasiswa'),
            Column::make('program.name_id')->title('Program Studi')->orderable(false),
            Column::make('entry_year')->title('Angkatan'),
            Column::make('status')->title('Status'),
            Column::computed('academic_advisor')->title('Dosen Wali Akademik')->orderable(false)->searchable(false),
            Column::computed('action')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Student_' . date('YmdHis');
    }
}
