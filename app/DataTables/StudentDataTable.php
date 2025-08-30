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
                $editUrl = route('admin.students.edit', $student->id);
                return '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';
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
        return $model->newQuery()->with(['user', 'program', 'academicAdvisor']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('student-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.students.data'))
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
            Column::computed('academic_advisor')->title('Dosen PA')->orderable(false)->searchable(false),
            Column::computed('action')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Student_' . date('YmdHis');
    }
}
