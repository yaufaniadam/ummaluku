<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(User $row){
                $editUrl = route('admin.sdm.staff.edit', $row->id);
                // We will implement delete via form submission for simplicity,
                // or livewire/js if preferred. Sticking to standard form for now.
                $csrf = csrf_field();
                $method = method_field('DELETE');
                $actionUrl = route('admin.sdm.staff.destroy', $row->id);

                $buttons = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm">Edit</a> ';
                $buttons .= '<form action="'.$actionUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin?\')">
                                '.$csrf.'
                                '.$method.'
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                             </form>';

                return $buttons;
            })
            ->editColumn('created_at', function(User $row) {
                return $row->created_at->format('d M Y');
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        // Filter users who have the 'Tendik' role
        return $model->newQuery()->role('Tendik');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('staff-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('admin.sdm.staff.data'))
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
            Column::make('name')->title('Nama'),
            Column::make('email')->title('Email'),
            Column::make('created_at')->title('Tanggal Bergabung'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Staff_' . date('YmdHis');
    }
}
