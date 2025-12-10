<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AcademicEventDataTable; 
use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use Illuminate\Http\Request;

class AcademicEventController extends Controller
{
    public function index(AcademicEventDataTable $dataTable)
    {
        return $dataTable->render('admin.academic-events.index');
    }

    public function create()
    {
        return view('admin.academic-events.create');
    }

    public function edit(AcademicEvent $academicEvent)
    {
        return view('admin.academic-events.edit', compact('academicEvent'));
    }

    public function feed(Request $request)
    {
        $events = AcademicEvent::all();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id'          => $event->id,
                'title' => $event->name,
                'start' => $event->start_date,
                // Tambahkan 1 hari ke end_date karena FullCalendar menganggap end date eksklusif
                'end' => $event->end_date ? (new \DateTime($event->end_date))->modify('+1 day')->format('Y-m-d') : null,
                'color' => $event->color,
                'description' => $event->description, 
            ];
        });

        return response()->json($formattedEvents);
    }
}

// edited by Adam