<?php

namespace App\DataTables;

use App\Models\Curriculum;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CurriculumDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Curriculum $row) {
                // Tombol "Kelola Mata Kuliah" akan kita fungsikan nanti
                $manageCoursesUrl = route('admin.curriculums.courses.index', $row->id);
                $editUrl = route('admin.curriculums.edit', $row->id);

                $buttons = '<a href="' . $manageCoursesUrl . '" class="btn btn-info btn-sm">Kelola MK</a> ';
                $buttons .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a> ';
                $buttons .= '<button class="btn btn-danger btn-sm">Hapus</button>'; // Akan kita fungsikan nanti

                return $buttons;
            })
            ->editColumn('program.name_id', function (Curriculum $row) {
                return $row->program->name_id ?? '-';
            })
            ->editColumn('is_active', function (Curriculum $row) {
                if ($row->is_active) {
                    return '<span class="badge badge-success">Aktif</span>';
                }
                return '<span class="badge badge-secondary">Tidak Aktif</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    public function query(Curriculum $model): QueryBuilder
    {
        // Eager load relasi 'program' untuk performa yang lebih baik
        return $model->newQuery()->with('program');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('curriculum-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('admin.curriculums.data'))
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('reload'),
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->width(50),
            Column::make('name')->title('Nama Kurikulum'),
            Column::make('program.name_id')->title('Program Studi')->orderable(false),
            Column::make('start_year')->title('Tahun Mulai'),
            Column::make('is_active')->title('Status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(250)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Curriculum_' . date('YmdHis');
    }
}