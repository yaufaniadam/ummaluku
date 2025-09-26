<?php

namespace App\DataTables;

use App\Models\AcademicEvent;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AcademicEventDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (AcademicEvent $row) {
                $editUrl = route('admin.academic-events.edit', $row->id);
                return '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';
            })
            ->editColumn('academicYear.name', function (AcademicEvent $row) {
                return $row->academicYear->name ?? '-';
            })
            ->editColumn('start_date', function (AcademicEvent $row) {
                return \Carbon\Carbon::parse($row->start_date)->isoFormat('D MMMM Y');
            })
            ->editColumn('end_date', function (AcademicEvent $row) {
                return $row->end_date ? \Carbon\Carbon::parse($row->end_date)->isoFormat('D MMMM Y') : '-';
            })
            ->setRowId('id');
    }

    public function query(AcademicEvent $model): QueryBuilder
    {
        return $model->newQuery()->with('academicYear');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('academicevent-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.academic-events.data'))
            ->dom('Bfrtip')
            ->orderBy(3, 'desc') // Urutkan berdasarkan tanggal mulai
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Nama Kegiatan'),
            Column::make('academicYear.name')->title('Semester'),
            Column::make('start_date')->title('Tanggal Mulai'),
            Column::make('end_date')->title('Tanggal Selesai'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'AcademicEvent_' . date('YmdHis');
    }
}
