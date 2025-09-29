<?php

namespace App\DataTables;

use App\Models\AcademicYear;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AcademicYearDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', function (AcademicYear $row) {
                return $row->is_active
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-secondary">Tidak Aktif</span>';
            })
            // ====================== TAMBAHKAN DUA BLOK INI ======================
            ->editColumn('krs_start_date', function (AcademicYear $row) {
                // Karena kita sudah pakai $casts di model, $row->krs_start_date adalah objek Carbon
                return $row->krs_start_date->isoFormat('D MMMM YYYY');
            })
            ->editColumn('krs_end_date', function (AcademicYear $row) {
                return $row->krs_end_date->isoFormat('D MMMM YYYY');
            })
            // ====================================================================
            ->addColumn('action', function (AcademicYear $row) {
                $editUrl = route('admin.akademik.academic-years.edit', $row->id);
                // Tombol "Kelola Semester" juga harusnya sudah ada dari langkah sebelumnya
                $manageUrl = route('admin.akademik.academic-years.show', $row->id);
                $buttons = '<a href="' . $manageUrl . '" class="btn btn-info btn-sm" wire:navigate>Kelola Semester</a> ';
                $buttons .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';
                return $buttons;
            })
            ->rawColumns(['is_active', 'action']) // Pastikan rawColumns tetap ada
            ->setRowId('id');
    }

    public function query(AcademicYear $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('academicyear-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.akademik.academic-years.data'))
            ->dom('Bfrtip')
            ->orderBy(0, 'desc') // Urutkan berdasarkan ID terbaru
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('year_code')->title('Kode'),
            Column::make('name')->title('Nama Tahun Ajaran'),
            Column::make('krs_start_date')->title('Mulai KRS'),
            Column::make('krs_end_date')->title('Selesai KRS'),
            Column::make('is_active')->title('Status'),
            Column::computed('action')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'AcademicYear_' . date('YmdHis');
    }
}
