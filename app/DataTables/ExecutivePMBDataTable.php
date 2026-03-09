<?php

namespace App\DataTables;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExecutivePMBDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('program', function ($row) {
                $firstChoice = $row->programChoices->first();
                return $firstChoice ? $firstChoice->program->name_id : '-';
            })
            ->editColumn('status', function ($row) {
                $badges = [
                    'pending' => '<span class="badge badge-warning">Pending</span>',
                    'diterima' => '<span class="badge badge-success">Diterima</span>',
                    'ditolak' => '<span class="badge badge-danger">Ditolak</span>',
                    'sudah_registrasi' => '<span class="badge badge-primary">Sudah Registrasi</span>',
                ];
                return $badges[$row->status] ?? '<span class="badge badge-secondary">' . $row->status . '</span>';
            })
            ->filterColumn('status', function($query, $keyword) {
                $query->where('status', $keyword);
            })
            ->rawColumns(['status']);
    }

    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['prospective.user', 'batch', 'admissionCategory', 'programChoices.program'])
            ->when(request('status'), fn($q, $v) => $q->where('status', $v));
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('executive-pmb-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('executive.pmb.data'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->parameters([
                'buttons' => [
                    ['extend' => 'excel', 'text' => '<i class="fas fa-file-excel"></i> Excel'],
                    ['extend' => 'csv', 'text' => '<i class="fas fa-file-csv"></i> CSV'],
                    ['extend' => 'print', 'text' => '<i class="fas fa-print"></i> Print'],
                    ['extend' => 'reload', 'text' => '<i class="fas fa-sync"></i> Reload']
                ],
                'initComplete' => "function() {
                    this.api().columns([6]).every(function() {
                        var column = this;
                        var select = $('<select class=\"form-control form-control-sm\"><option value=\"\">Semua Status</option></select>')
                            .appendTo($('.card-header'))
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? val : '', true, false).draw();
                            });
                        
                        select.append('<option value=\"pending\">Pending</option>');
                        select.append('<option value=\"diterima\">Diterima</option>');
                        select.append('<option value=\"ditolak\">Ditolak</option>');
                        select.append('<option value=\"sudah_registrasi\">Sudah Registrasi</option>');
                    });
                }"
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false)->width(50),
            Column::make('registration_number')->title('No. Registrasi'),
            Column::make('prospective.user.name')->title('Nama'),
            Column::make('admission_category.name')->title('Jalur'),
            Column::make('batch.name')->title('Gelombang'),
            Column::computed('program')->title('Prodi Pilihan 1'),
            Column::make('status')->title('Status'),
        ];
    }
}
