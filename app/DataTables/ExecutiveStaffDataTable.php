<?php

namespace App\DataTables;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExecutiveStaffDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(Staff $row){
                // Read-only: Only show Detail button for executive view
                $showUrl = route('executive.staff.show', $row->id);
                return '<a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>';
            })
            ->editColumn('created_at', function(Staff $row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('name', function(Staff $row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('email', function(Staff $row) {
                return $row->user->email ?? '-';
            })
            ->addColumn('unit', function(Staff $row) {
                if ($row->workUnit) {
                    return $row->workUnit->name;
                } elseif ($row->program) {
                    return $row->program->name_id . ' (Prodi)';
                }
                return '-';
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(Staff $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'workUnit', 'program']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('executive-staff-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('executive.staff.data'))
                    ->dom('Bfrtip')
                    ->orderBy(0)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('nip')->title('NIP'),
            Column::make('name')->title('Nama'),
            Column::make('unit')->title('Unit Kerja'),
            Column::make('phone')->title('No. HP'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Executive_Staff_' . date('YmdHis');
    }
}
