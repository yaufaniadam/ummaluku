<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\AdmissionCategory; 
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::latest()->paginate(10);
        return view('admin.pmb.batches.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.pmb.batches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'year' => 'required|digits:4|integer|min:2020',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        Batch::create($validated);

        return redirect()->route('admin.pmb.gelombang.index')->with('success', 'Gelombang berhasil ditambahkan.');
    }

    public function edit(Batch $gelombang)
    {
        return view('admin.pmb.batches.edit', ['batch' => $gelombang]);
    }

    public function update(Request $request, Batch $gelombang)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'year' => 'required|digits:4|integer|min:2020',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        $gelombang->update($validated);

        return redirect()->route('admin.pmb.gelombang.index')->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy(Batch $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('admin.pmb.gelombang.index')->with('success', 'Gelombang berhasil dihapus.');
    }
}