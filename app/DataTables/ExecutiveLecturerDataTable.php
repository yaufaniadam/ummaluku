<?php

namespace App\DataTables;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExecutiveLecturerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(Lecturer $row){
                // Read-only: Only show Detail button for executive view
                $showUrl = route('executive.lecturers.show', $row->id);
                return '<a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>';
            })
            ->editColumn('status', function (Lecturer $row) {
                $status = $row->status;
                $badgeClass = 'badge-secondary';
                if ($status === 'active') {
                    $badgeClass = 'badge-success';
                    $status = 'Aktif';
                } elseif ($status === 'on_leave') {
                    $badgeClass = 'badge-warning';
                    $status = 'Non-Aktif';
                } elseif ($status === 'retired') {
                    $badgeClass = 'badge-danger';
                    $status = 'Pensiun';
                }
                return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
            })
            ->addColumn('program', function($row){
                return $row->program->name_id ?? '-';
            })
            ->addColumn('user.email', function($row){
                return $row->user->email ?? '-';
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    public function query(Lecturer $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'program']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('executive-lecturer-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('executive.lecturers.data'))
                    ->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('nidn'),
            Column::make('full_name_with_degree')->title('Nama Lengkap'),
            Column::make('user.email')->title('Email'),
            Column::make('program')->title('Program Studi'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Executive_Lecturer_' . date('YmdHis');
    }
}
