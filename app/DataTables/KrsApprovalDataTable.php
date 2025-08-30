<?php

namespace App\DataTables;

use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KrsApprovalDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $activeSemesterId = AcademicYear::where('is_active', true)->first()->id;

        return (new EloquentDataTable($query))
            ->addColumn('action', function (Student $student) {
                $url = route('dosen.krs-approval.show', $student->id);
                return '<a href="' . $url . '" class="btn btn-primary btn-sm" wire:navigate>Lihat & Proses</a>';
            })
            ->addColumn('total_sks', function (Student $student) use ($activeSemesterId) {
                // Load relasi enrollments dengan filter semester aktif dan status pending
                $student->load(['enrollments' => function ($query) use ($activeSemesterId) {
                    $query->where('academic_year_id', $activeSemesterId)
                        ->where('status', 'pending')
                        ->with('courseClass.course');
                }]);
                // Hitung total SKS dari relasi yang sudah di-load
                return $student->enrollments->sum('courseClass.course.sks') . ' SKS';
            })
            ->editColumn('user.name', function (Student $student) {
                return $student->user->name;
            })
            ->setRowId('id');
    }

    public function query(Student $model): QueryBuilder
    {
        // Jika tidak ada semester aktif, jangan tampilkan apa-apa
        $activeSemester = AcademicYear::where('is_active', true)->first();
        if (!$activeSemester) {
            return $model->newQuery()->where('id', -1); // Query kosong
        }

        // Dapatkan ID Dosen PA yang sedang login
        $lecturerId = optional(auth()->user()->lecturer)->id;
        if (!$lecturerId) {
            return $model->newQuery()->where('id', -1); // kosong
        }


        // Query mahasiswa yang:
        // 1. Dibimbing oleh dosen yang login
        // 2. Memiliki data enrollment di semester aktif
        // 3. Status enrollment-nya 'pending'
        return $model->newQuery()
            ->with('user')
            ->where('academic_advisor_id', $lecturerId)
            ->whereHas('enrollments', function ($q) use ($activeSemester) {
                $q->where('academic_year_id', $activeSemester->id)
                    ->where('status', 'pending');
            })
            ->distinct(); // Pastikan mahasiswa tidak muncul dua kali
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('krsapproval-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([Button::make('reload')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Student ID')->visible(true),
            Column::make('nim')->title('NIM'),
            Column::make('user.name')->title('Nama Mahasiswa'),
            Column::make('entry_year')->title('Angkatan'),
            Column::computed('total_sks')->title('SKS Diajukan')->orderable(false)->searchable(false),
            Column::computed('action')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'KrsApproval_' . date('YmdHis');
    }
}
