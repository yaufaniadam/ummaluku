<?php

namespace App\DataTables;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ApplicationsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() // Menambahkan kolom nomor urut
            ->addColumn('action', function ($row) {
                // Membuat tombol aksi untuk setiap baris
                $viewUrl = route('admin.pendaftaran.show', $row->id);
                $button = ''; // Initialize an empty string for the button

                // Only show the "Lihat Detail" button if the status is 'menunggu_verifikasi'
                if (($row->status === 'proses_verifikasi') || ($row->status === 'completed') || ($row->status === 'lolos_verifikasi')) {
                    $button = '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Lihat Detail</a>';
                }
                return $button;
            })
            ->editColumn('prospective.user.name', function ($row) {
                // Membuat nama pendaftar bisa diklik dan mengarah ke detail
                return '<a href="' . route('admin.pendaftaran.show', $row->id) . '">' . $row->prospective->user->name . '</a>';
            })
            ->editColumn('status', function ($row) {
                // Mengubah tampilan status menjadi badge
                return '<span class="badge badge-info">' . \Str::title(str_replace('_', ' ', $row->status)) . '</span>';
            })
            // Memberitahu Yajra bahwa kolom action dan status berisi HTML
            ->rawColumns(['action', 'status', 'prospective.user.name']);
    }

    /**
     * Mengambil query data pendaftar.
     */
    public function query(Application $model): QueryBuilder
    {
        // Kita mulai dengan query dasar
        $query = $model->newQuery()->with(['prospective.user', 'batch', 'admissionCategory']);

        // Ambil nilai status dari request, jika tidak ada, gunakan default
        $status = request('status', 'lengkapi_data');

        // Terapkan filter secara kondisional
        $query->when(request('category'), function ($q, $categoryId) {
            return $q->where('admission_category_id', $categoryId);
        });

        $query->when(request('batch'), function ($q, $batchId) {
            return $q->where('batch_id', $batchId);
        });

        // Terapkan filter status, KECUALI jika nilainya adalah string kosong
        $query->when($status, function ($q, $statusValue) {
            return $q->where('status', $statusValue);
        });

        return $query;
    }

    /**
     * Mendefinisikan struktur HTML tabel.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('applications-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->ajax([
                'data' => "
                function(d) {
                    d.category = $('#category-filter').val();
                    d.batch = $('#batch-filter').val();
                    d.status = $('#status-filter').val();
                }
            "
            ])
            ->buttons([
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Mendefinisikan kolom-kolom tabel.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('registration_number')->title('No. Registrasi'),
            Column::make('prospective.user.name')->title('Nama Pendaftar'),
            Column::make('admission_category.name')->title('Jalur'),
            Column::make('batch.name')->title('Gelombang'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Applications_' . date('YmdHis');
    }
}
