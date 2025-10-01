<?php

namespace App\DataTables;

use App\Models\AcademicInvoice;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;

class AcademicInvoiceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(AcademicInvoice $invoice) {
                // Tampilkan tombol aksi hanya jika statusnya 'verify'
                if ($invoice->status === 'verify') {
                    $payment = $invoice->payments()->where('status', 'pending')->latest()->first();
                    if ($payment) {
                        $approveUrl = route('admin.keuangan.payment-verification.approve', $payment->id);
                        $rejectUrl = route('admin.keuangan.payment-verification.reject', $payment->id);
                        $csrf = csrf_field();
                        
                        $approveBtn = '
                            <form action="'.$approveUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\'Anda yakin ingin menyetujui pembayaran ini?\');">
                                '.$csrf.'
                                <button type="submit" class="btn btn-success btn-xs">Setujui</button>
                            </form>';
                        
                        $rejectBtn = '
                            <form action="'.$rejectUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\'Anda yakin ingin menolak pembayaran ini?\');">
                                '.$csrf.'
                                <button type="submit" class="btn btn-danger btn-xs">Tolak</button>
                            </form>';

                        return $approveBtn . ' ' . $rejectBtn;
                    }
                }
                // Jika status lain, tampilkan strip
                return '-';
            })
            ->addColumn('proof', function(AcademicInvoice $invoice) {
                // Ambil pembayaran terakhir yang statusnya pending
                $payment = $invoice->payments()->where('status', 'pending')->latest()->first();
                if ($payment && $payment->proof_url) {
                    $url = Storage::url($payment->proof_url);
                    return '<a href="'.$url.'" target="_blank" class="btn btn-info btn-xs">Lihat Bukti</a>';
                }
                return '-';
            })
            ->editColumn('student.user.name', function(AcademicInvoice $invoice) {
                return $invoice->student->user->name ?? '-';
            })
            ->editColumn('total_amount', function(AcademicInvoice $invoice) {
                return 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
            })
            ->editColumn('status', function (AcademicInvoice $invoice) {
                $statusMap = [
                    'unpaid' => ['class' => 'secondary', 'text' => 'Belum Lunas'],
                    'paid' => ['class' => 'success', 'text' => 'Lunas'],
                    'overdue' => ['class' => 'danger', 'text' => 'Jatuh Tempo'],
                    'verify' => ['class' => 'warning', 'text' => 'Menunggu Verifikasi'],
                ];
                $status = $statusMap[$invoice->status] ?? ['class' => 'dark', 'text' => 'N/A'];
                return '<span class="badge badge-' . $status['class'] . '">' . $status['text'] . '</span>';
            })
            ->rawColumns(['action', 'status', 'proof'])
            ->setRowId('id');
    }

    public function query(AcademicInvoice $model): QueryBuilder
    {
        // Eager load relasi pembayaran agar lebih efisien
        return $model->newQuery()->with(['student.user', 'academicYear', 'payments'])->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('academicinvoice-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('admin.keuangan.payment-verification.data'))
                    ->dom('Bfrtip')
                    ->orderBy(0, 'desc')
                    ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('invoice_number')->title('No. Tagihan'),
            Column::make('student.user.name')->title('Nama Mahasiswa')->orderable(false),
            Column::make('total_amount')->title('Jumlah'),
            Column::make('status')->title('Status'),
            Column::computed('proof')->title('Bukti Bayar')->orderable(false)->searchable(false),
            Column::computed('action')->addClass('text-center'),
        ];
    }
}