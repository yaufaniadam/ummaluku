<?php

namespace App\DataTables;

use App\Models\FeeComponent;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeComponentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (FeeComponent $row) {
                $editUrl = route('admin.keuangan.fee-components.edit', $row->id);
                return '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';
            })
            ->setRowId('id');
    }

    public function query(FeeComponent $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('feecomponent-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.keuangan.fee-components.data'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Nama Komponen Biaya'),
            Column::make('type')->title('Tipe'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'FeeComponent_' . date('YmdHis');
    }
}
