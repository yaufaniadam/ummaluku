<?php

namespace App\DataTables;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdvisedStudentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(Student $student) {
                // Tombol ini bisa kita kembangkan nanti untuk melihat detail/transkrip mahasiswa
                return '<a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>';
            })
            ->editColumn('user.name', function (Student $student) {
                return $student->user->name;
            })
            ->editColumn('program.name_id', function(Student $student) {
                return $student->program->name_id ?? '-';
            })
            ->setRowId('id');
    }

    public function query(Student $model): QueryBuilder
    {
        // Dapatkan ID Dosen PA yang sedang login
        $lecturerId = auth()->user()->lecturer->id;

        // Query semua mahasiswa yang dibimbing oleh dosen yang sedang login
        return $model->newQuery()
            ->with(['user', 'program'])
            ->where('academic_advisor_id', $lecturerId);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('advisedstudent-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([Button::make('reload'), Button::make('excel'), Button::make('print')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('nim')->title('NIM'),
            Column::make('user.name')->title('Nama Mahasiswa'),
            Column::make('program.name_id')->title('Program Studi')->orderable(false),
            Column::make('entry_year')->title('Angkatan'),
            Column::make('status')->title('Status Akademik'),
            Column::computed('action')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'AdvisedStudent_' . date('YmdHis');
    }
}