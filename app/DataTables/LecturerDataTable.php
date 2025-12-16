<?php

namespace App\DataTables;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LecturerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(Lecturer $row){
                $showUrl = route('admin.sdm.lecturers.show', $row->id);
                $editUrl = route('admin.sdm.lecturers.edit', $row->id);
                $toggleStatusEvent = "Livewire.dispatch('confirm-toggle-status', { lecturer: {$row->id} })";
                $deleteEvent = "Livewire.dispatch('confirm-delete', { lecturer: {$row->id} })";

                $toggleBtnText = $row->status === 'active' ? 'Non-Aktifkan' : 'Aktifkan';
                $toggleBtnClass = $row->status === 'active' ? 'btn-warning' : 'btn-success';

                $buttons = '<a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a> ';
                $buttons .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm" wire:navigate>Edit</a> ';
                $buttons .= '<button onclick="' . $toggleStatusEvent . '" class="btn ' . $toggleBtnClass . ' btn-sm">' . $toggleBtnText . '</button> ';
                $buttons .= '<button onclick="' . $deleteEvent . '" class="btn btn-danger btn-sm">Hapus</button>';

                return $buttons;
            })
            ->editColumn('status', function (Lecturer $row) {
                $status = $row->status;
                $badgeClass = 'badge-secondary';
                if ($status === 'active') {
                    $badgeClass = 'badge-success';
                    $status = 'Aktif';
                } elseif ($status === 'on_leave') {
                    $badgeClass = 'badge-warning';
                    $status = 'Non-Aktif';
                } elseif ($status === 'retired') {
                    $badgeClass = 'badge-danger';
                    $status = 'Pensiun';
                }
                return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
            })
            ->addColumn('program', function($row){
                return $row->program->name_id ?? '-';
            })
            ->addColumn('user.email', function($row){
                return $row->user->email ?? '-';
            })
            ->rawColumns(['status', 'action']) // Penting agar HTML di kolom status & action di-render
            ->setRowId('id');
    }

    public function query(Lecturer $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'program']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('lecturer-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('admin.sdm.lecturers.data'))
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('nidn'),
            Column::make('full_name_with_degree')->title('Nama Lengkap'),
            Column::make('user.email')->title('Email'),
            Column::make('program')->title('Program Studi'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(220) // Lebarkan kolom aksi
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Lecturer_' . date('YmdHis');
    }
}