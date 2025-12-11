<?php

namespace App\DataTables;

use App\Models\ReRegistrationInvoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReRegistrationInvoicesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                // Perbaiki nama route di sini
                return '<a href="' . route('admin.pmb.payment.show', $row) . '" class="btn btn-primary btn-sm">Lihat & Verifikasi</a>';
            })
            // Kolom baru untuk menghitung jumlah yang sudah dibayar
            ->addColumn('amount_paid', function ($row) {
                // Hitung jumlah dari semua cicilan yang statusnya 'paid'
                $paidAmount = $row->installments()->where('status', 'paid')->sum('amount');
                return 'Rp ' . number_format($paidAmount, 0, ',', '.');
            })
            ->editColumn('amount', fn($row) => 'Rp ' . number_format($row->total_amount, 0, ',', '.'))
            ->editColumn('status', function ($row) {
                if ($row->status == 'paid') {
                    return '<span class="badge badge-success">Lunas</span>';
                } elseif ($row->status == 'pending_verification') {
                    return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
                } elseif ($row->status == 'partially_paid') {
                    return '<span class="badge badge-info">Dibayar Sebagian</span>';
                } elseif ($row->status == 'rejected') {
                    return '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    return '<span class="badge badge-secondary">Belum Dibayar</span>';
                }
            })
            ->rawColumns(['action', 'status']); // Pastikan 'status' ada di rawColumns agar HTML bisa dirender
    }

    public function query(ReRegistrationInvoice $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('application.prospective.user')
            // ->where('status', 'pending_verification')
            ->when(request('status'), function ($query, $status) {
                return $query->where('status', $status);
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reregistrationinvoices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ajax([
                'data' => "
                    function(d) {
                        d.status = $('#payment-status-filter').val();
                    }
                "
            ])
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('invoice_number')->title('No. Tagihan'),
            Column::make('application.prospective.user.name')->title('Nama Mahasiswa'),
            Column::make('amount')->title('Total Tagihan'),
             Column::computed('amount_paid')->title('Sudah Dibayar'),
            Column::make('status')->title('Status'),
            Column::computed('action')->addClass('text-center'),
        ];
    }
}
