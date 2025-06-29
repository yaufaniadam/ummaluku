<?php

namespace App\DataTables;

use App\Models\Student;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentsDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                // Tombol untuk melihat detail akademik mahasiswa (nanti)
                return '<a href="#" class="btn btn-info btn-sm">Lihat Detail</a>';
            });
    }

    public function query(Student $model)
    {
        // Ambil data mahasiswa beserta relasi ke user dan program studi
        return $model->newQuery()->with(['user', 'program']);
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('students-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('nim')->title('NIM'), // <-- Menampilkan Nomor Induk Mahasiswa
            Column::make('user.name')->title('Nama Mahasiswa'),
            Column::make('program.name_id')->title('Program Studi'),
            Column::make('entry_year')->title('Tahun Masuk'),
            Column::make('status')->title('Status Akademik'),
            Column::computed('action')->addClass('text-center'),
        ];
    }
}