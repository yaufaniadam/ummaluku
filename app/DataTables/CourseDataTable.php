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
                $editUrl = route('admin.akademik.curriculums.courses.edit', ['curriculum' => $row->curriculum_id, 'course' => $row->id]);

                // Event untuk memicu modal hapus
                $deleteEvent = "Livewire.dispatch('confirm-delete-course', { course: {$row->id} })";

                $buttons = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a> ';
                // $buttons .= '<button onclick="' . $deleteEvent . '" class="btn btn-danger btn-sm">Hapus</button>';

                return $buttons;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Course $model): QueryBuilder
    {
        // ====================== PERBAIKAN UTAMA DI SINI ======================
        // Mengambil 'curriculum' dari parameter route yang aktif saat ini.
        // Ini memastikan query SELALU menggunakan ID kurikulum yang benar,
        // bahkan saat di-reload melalui AJAX.
        $curriculumId = $this->request()->route('curriculum');

        return $model->newQuery()->where('curriculum_id', $curriculumId);
        // ====================================================================
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
            ->minifiedAjax(route('admin.akademik.curriculums.courses.data', ['curriculum' => $curriculumId]))
            ->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('id')->width(50),
            Column::make('code')->title('Kode MK'),
            Column::make('name')->title('Nama Mata Kuliah'),
            Column::make('sks'),
            Column::make('semester_recommendation')->title('Semester'),
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
