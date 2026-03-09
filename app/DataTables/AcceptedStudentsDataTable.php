<?php

namespace App\DataTables;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AcceptedStudentsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                // Membuat tombol aksi untuk setiap baris
                $viewUrl = route('admin.pmb.pendaftaran.show', $row->id);
                return '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Lihat Detail</a>';
            })
            ->addColumn('accepted_program', function ($row) {
                // Cari dan tampilkan prodi mana pendaftar ini diterima
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
                // Cek status dari relasi reRegistrationInvoice
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
            ->addColumn('action', function ($row) {
                if ($row->status == 'sudah_registrasi') {
                    return '<button class="btn btn-success btn-sm" disabled>Sudah Registrasi</button>';
                }

                $invoice = $row->reRegistrationInvoice;
                // Tombol finalisasi sekarang menjadi form yang hanya aktif jika status lunas
                if ($invoice && ($invoice->status == 'paid' || $invoice->status == 'partially_paid')) {
                    $finalizeUrl = route('admin.pmb.accepted.finalize', $row->id);
                    return '
                    <form action="' . $finalizeUrl . '" method="POST">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm(\'Anda yakin ingin memfinalisasi mahasiswa ini dan membuat NIM?\')">
                            Finalisasi Registrasi
                        </button>
                    </form>
                ';
                }
                return '<button class="btn btn-secondary btn-sm" disabled>Pending</button>';
            })

           
            ->rawColumns(['action', 'status', 'payment_status']);
    }

    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['prospective.user', 'batch', 'admissionCategory', 'programChoices.program', 'reRegistrationInvoice'])
            ->whereIn('status', ['diterima', 'sudah_registrasi'])
            ->when(request('category'), fn($q, $v) => $q->where('admission_category_id', $v))
            ->when(request('batch'), fn($q, $v) => $q->where('batch_id', $v))
            ->when(request('program'), function ($q, $programId) {
                // Filter berdasarkan prodi yang diterima
                return $q->whereHas('programChoices', function ($query) use ($programId) {
                    $query->where('program_id', $programId)->where('is_accepted', true);
                });
            })
            ->when(request('payment_status'), function ($q, $status) {
                // Filter berdasarkan status di tabel reRegistrationInvoice
                return $q->whereHas('reRegistrationInvoice', function ($query) use ($status) {
                    $query->where('status', $status);
                });
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('acceptedstudents-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ajax([
                'data' => "
                    function(d) {
                        d.category = $('#category-filter').val();
                        d.batch = $('#batch-filter').val();
                        d.program = $('#program-filter').val();
                        d.payment_status = $('#payment-status-filter').val();
                    }
                "
            ])
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('export'), Button::make('print')]);
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
            Column::computed('action')->addClass('text-center'),
        ];
    }
}
