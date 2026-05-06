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
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $viewUrl = route('admin.pmb.pendaftaran.show', $row->id);
                $button = '';
                if (in_array($row->status, ['proses_verifikasi', 'sudah_registrasi', 'lolos_verifikasi'])) {
                    $button = '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Lihat Detail</a>';
                }
                return $button;
            })
            ->editColumn('prospective.user.name', function ($row) {
                return '<a href="' . route('admin.pmb.pendaftaran.show', $row->id) . '">' . $row->prospective->user->name . '</a>';
            })
            ->editColumn('status', function ($row) {
                return '<span class="badge badge-info">' . \Str::title(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->editColumn('admission_category_name', function ($row) {
                return $row->admission_category_name ?? '-';
            })
            ->editColumn('batch_name', function ($row) {
                return $row->batch_name ?? '-';
            })
            ->filterColumn('admission_category_name', function ($query, $keyword) {
                $query->where('admission_categories.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('batch_name', function ($query, $keyword) {
                $query->where('batches.name', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action', 'status', 'prospective.user.name']);
    }

    /**
     * Mengambil query data pendaftar.
     */
    public function query(Application $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->select('applications.*')
            ->with(['prospective.user'])
            ->leftJoin('admission_categories', 'applications.admission_category_id', '=', 'admission_categories.id')
            ->leftJoin('batches', 'applications.batch_id', '=', 'batches.id')
            ->selectRaw('admission_categories.name as admission_category_name')
            ->selectRaw('batches.name as batch_name');

        $query->when(request('status'), function ($q, $status) {
            return $q->where('applications.status', $status);
        });

        $query->when(request('category'), function ($q, $categoryId) {
            return $q->where('applications.admission_category_id', $categoryId);
        });

        $query->when(request('batch'), function ($q, $batchId) {
            return $q->where('applications.batch_id', $batchId);
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
            Column::make('admission_category_name')->title('Jalur'),
            Column::make('batch_name')->title('Gelombang'),
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
