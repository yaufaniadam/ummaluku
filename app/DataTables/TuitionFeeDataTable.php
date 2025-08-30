<?php

namespace App\DataTables;

use App\Models\FeeStructure; // <-- Ganti modelnya
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TuitionFeeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (FeeStructure $row) {
                $editUrl = route('admin.tuition-fees.edit', $row->id);
                $deleteUrl = route('admin.tuition-fees.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $editBtn = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a>';

                // Buat form kecil untuk tombol hapus
                $deleteBtn = '
            <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\');">
                ' . $csrf . '
                ' . $method . '
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        ';

                return $editBtn . ' ' . $deleteBtn;
            })
            ->editColumn('program.name_id', function (FeeStructure $row) {
                return $row->program->name_id ?? '-';
            })
            ->editColumn('feeComponent.name', function (FeeStructure $row) {
                return $row->feeComponent->name ?? '-';
            })
            ->editColumn('amount', function (FeeStructure $row) {
                return 'Rp ' . number_format($row->amount, 0, ',', '.');
            })
            ->setRowId('id');
    }

    public function query(FeeStructure $model): QueryBuilder // <-- Ganti modelnya
    {
        return $model->newQuery()->with(['program', 'feeComponent']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tuitionfee-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.tuition-fees.data'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('program.name_id')->title('Program Studi'),
            Column::make('entry_year')->title('Tahun Angkatan'),
            Column::make('feeComponent.name')->title('Komponen Biaya'), // <-- Kolom baru
            Column::make('amount')->title('Jumlah Biaya'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'FeeStructure_' . date('YmdHis'); // Ganti nama file export
    }
}
