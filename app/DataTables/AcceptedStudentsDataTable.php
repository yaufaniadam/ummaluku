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
                $viewUrl = route('admin.pendaftaran.show', $row->id);
                return '<a href="' . $viewUrl . '" class="btn btn-info btn-sm">Lihat Detail</a>';
            })
            ->addColumn('accepted_program', function ($row) {
                // Cari dan tampilkan prodi mana pendaftar ini diterima
                $acceptedChoice = $row->programChoices->where('is_accepted', true)->first();
                return $acceptedChoice ? $acceptedChoice->program->name_id : 'N/A';
            })
            ->editColumn('status', fn($row) => '<span class="badge badge-success">Diterima</span>')
            ->rawColumns(['action', 'status']);
    }

    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['prospective.user', 'batch', 'admissionCategory', 'programChoices.program'])
            ->where('status', 'accepted') // <-- Filter utama: hanya yang statusnya 'accepted'
            ->when(request('category'), fn($q, $v) => $q->where('admission_category_id', $v))
            ->when(request('batch'), fn($q, $v) => $q->where('batch_id', $v))
            ->when(request('program'), function ($q, $programId) {
                // Filter berdasarkan prodi yang diterima
                return $q->whereHas('programChoices', function($query) use ($programId) {
                    $query->where('program_id', $programId)->where('is_accepted', true);
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
            Column::computed('action')->addClass('text-center'),
        ];
    }
}   