<?php

namespace App\Livewire\Admin\AcademicEvent;

use App\Models\AcademicEvent;
use App\Models\AcademicYear;
use Livewire\Component;

class AcademicEventForm extends Component
{
    public ?AcademicEvent $academicEvent = null;
    public $academicYears;

    public $colorPalette = [
        '#007bff' => '',
        '#28a745' => '',
        '#ffc107' => '',
        '#dc3545' => '',
        '#6c757d' => '',
        '#17a2b8' => '',
    ];

    // Form properties
    public $academic_year_id;
    public $name;
    public $color;
    public $start_date;
    public $end_date;
    public $description;

    public function mount(AcademicEvent $academicEvent = null)
    {
        $this->academicYears = AcademicYear::orderBy('year_code', 'desc')->get();
        if ($academicEvent->exists) {
            $this->academicEvent = $academicEvent;
            $this->academic_year_id = $academicEvent->academic_year_id;
            $this->name = $academicEvent->name;
            $this->color = $academicEvent->color;
            $this->start_date = $academicEvent->start_date->format('Y-m-d');
            $this->end_date = $academicEvent->end_date ? $academicEvent->end_date->format('Y-m-d') : null;
            $this->description = $academicEvent->description;
        }
    }

    protected function rules()
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|size:7',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->academicEvent) {
            $this->academicEvent->update($validatedData);
            session()->flash('success', 'Event akademik berhasil diperbarui.');
        } else {
            AcademicEvent::create($validatedData);
            session()->flash('success', 'Event akademik berhasil ditambahkan.');
        }

        $this->dispatch('academic-event-updated');
        return $this->redirect(route('admin.academic-events.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.academic-event.academic-event-form');
    }
}