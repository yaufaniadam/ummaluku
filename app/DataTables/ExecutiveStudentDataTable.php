<?php

namespace App\DataTables;

use App\Models\Student;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExecutiveStudentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function (Student $row) {
                $showUrl = route('executive.students.show', $row->id);
                return '<a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>';
            })
            ->rawColumns(['action']);
    }

    public function query(Student $model)
    {
        return $model->newQuery()->with(['user', 'program']);
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('executive-students-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('executive.students.data'))
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('nim')->title('NIM'),
            Column::make('user.name')->title('Nama Mahasiswa'),
            Column::make('program.name_id')->title('Program Studi'),
            Column::make('entry_year')->title('Tahun Masuk'),
            Column::make('status')->title('Status'),
            Column::computed('action')->title('Aksi')->addClass('text-center'),
        ];
    }
}
