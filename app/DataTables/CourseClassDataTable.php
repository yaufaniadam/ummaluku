<?php

namespace App\DataTables;

use App\Models\CourseClass;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CourseClassDataTable extends DataTable
{
    public $academic_year_id;
    public $program_id;

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (CourseClass $row) {
                $editUrl = route('admin.academic-years.programs.course-classes.edit', [
                    'academic_year' => $row->academic_year_id,
                    'program' => $this->program_id,
                    'course_class' => $row->id
                ]);
                $deleteEvent = "Livewire.dispatch('confirm-delete-course-class', { courseClass: {$row->id} })";

                $buttons = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a> ';
                $buttons .= '<button onclick="' . $deleteEvent . '" class="btn btn-danger btn-sm">Hapus</button>';

                return $buttons;
            });
    }

    public function query(CourseClass $model): QueryBuilder
    {
        // Query sekarang jauh lebih spesifik
        return $model->newQuery()
            ->where('academic_year_id', $this->academic_year_id)
            ->whereHas('course.curriculum', function ($query) {
                $query->where('program_id', $this->program_id);
            })
            ->with(['course', 'lecturer']);
    }

    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('courseclass-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.academic-years.programs.course-classes.data', [
                'academic_year' => $this->academic_year_id,
                'program' => $this->program_id
            ]))
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Nama Kelas'),
            Column::make('course.name')->title('Mata Kuliah')->orderable(false),
            Column::make('lecturer.full_name_with_degree')->title('Dosen Pengampu')->orderable(false),
            Column::make('capacity')->title('Kapasitas'),
            Column::make('day')->title('Hari'),
            Column::make('start_time')->title('Mulai'),
            Column::make('end_time')->title('Selesai'),
            Column::make('room')->title('Ruangan'),
            Column::computed('action')->addClass('text-center')->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'CourseClass_' . date('YmdHis');
    }
}
