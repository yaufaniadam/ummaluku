<?php

namespace App\DataTables;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExecutiveAcceptedStudentsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('accepted_program', function ($row) {
                $acceptedChoice = $row->programChoices->where('is_accepted', true)->first();
                return $acceptedChoice ? $acceptedChoice->program->name_id : 'N/A';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'sudah_registrasi') {
                    return '<span class="badge badge-primary">Sudah Registrasi</span>';
                }
                return '<span class="badge badge-success">Diterima</span>';
            })
            ->addColumn('payment_status', function ($row) {
                $invoice = $row->reRegistrationInvoice;
                if (!$invoice) {
                    return '<span class="badge badge-secondary">Belum Dibuat</span>';
                }

                if ($invoice->status == 'paid') {
                    return '<span class="badge badge-success">Lunas</span>';
                } elseif ($invoice->status == 'pending_verification') {
                    return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
                } elseif ($invoice->status == 'partially_paid') {
                    return '<span class="badge badge-info">Dibayar Sebagian</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Dibayar</span>';
                }
            })
            ->rawColumns(['status', 'payment_status']);
    }

    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['prospective.user', 'batch', 'admissionCategory', 'programChoices.program', 'reRegistrationInvoice'])
            ->whereIn('status', ['diterima', 'sudah_registrasi']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('executive-accepted-students-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('executive.admisi.data'))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('print'), Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('registration_number')->title('No. Registrasi'),
            Column::make('prospective.user.name')->title('Nama Mahasiswa'),
            Column::make('admission_category.name')->title('Jalur'),
            Column::make('batch.name')->title('Gelombang'),
            Column::computed('accepted_program')->title('Diterima di Prodi'),
            Column::make('status')->title('Status'),
            Column::make('payment_status')->title('Status Pembayaran'),
        ];
    }
}
