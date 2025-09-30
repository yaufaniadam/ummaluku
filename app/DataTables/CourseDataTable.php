<?php

namespace App\DataTables;

use App\Models\Course;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CourseDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Course $row) {
                $editUrl = route(
                    'admin.akademik.courses.edit',
                    ['curriculum' => $row->curriculum_id, 'course' => $row->id]
                );

                $buttons = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a> ';

                return $buttons;
            })
            ->addColumn('program', function (Course $row) {
                // Jika program_id ada, tampilkan nama prodi. Jika tidak, tampilkan 'Universitas'.
                return $row->program->name_id ?? '<span class="badge badge-secondary">Universitas</span>';
            })
            ->rawColumns(['action', 'program'])
            ->filterColumn('program', function ($query, $keyword) {
                if ($keyword === 'universitas') {
                    $query->whereNull('program_id')->orWhere('program_id', 0);
                } elseif (!empty($keyword)) {
                    $query->where('program_id', $keyword);
                }
            });
        // ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Course $model): QueryBuilder
    {
        return $model->newQuery()->with('program')
            ->when($this->request()->get('program_id'), function ($query, $programId) {
                // Jika programId adalah string kosong, jangan lakukan apa-apa
                if ($programId === '') {
                    return;
                }

                if ($programId === 'universitas') {
                    return $query->whereNull('program_id');
                }

                return $query->where('program_id', $programId);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        // Mengambil ID kurikulum dari parameter route untuk membangun URL AJAX yang benar
        $curriculumId = $this->request()->route('curriculum')->id ?? $this->request()->route('curriculum');

        return $this->builder()
            ->setTableId('course-table')
            ->columns($this->getColumns())
            // Pastikan URL AJAX selalu benar saat halaman di-load
            ->minifiedAjax(route('admin.akademik.courses.data'))
            ->dom('Bfrtip')
            ->orderBy(4, 'asc')
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            //  Column::make('id')->width(50),
            Column::make('code')->title('Kode MK'),
            Column::make('name')->title('Nama Mata Kuliah'),
            Column::make('sks'),
            Column::make('semester_recommendation')->title('Semester'),
            Column::computed('program')->title('Cakupan/Prodi'),
            Column::computed('activity_type')->title('Tipe'),
            Column::make('type')->title('Jenis'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Course_' . date('YmdHis');
    }
}
