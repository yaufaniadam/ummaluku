<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use App\Models\DocumentRequirement;
use Illuminate\Http\Request;

class AdmissionCategoryController extends Controller
{
    public function index()
    {
        $categories = AdmissionCategory::with('batches')->latest()->paginate(10);
        return view('admin.pmb.categories.index', compact('categories'));
    }

    public function create()
    {
        $documents = DocumentRequirement::all();
        return view('admin.pmb.categories.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admission_categories,name',
            'description' => 'nullable|string',
            'display_group' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'price' => 'required|numeric',
            'documents' => 'nullable|array',
            'documents.*' => 'exists:document_requirements,id',
        ]);

        $category = AdmissionCategory::create($validated);

        if ($request->has('documents')) {
            $category->documentRequirements()->sync($request->documents);
        }

        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil ditambahkan.');
    }

    public function edit(AdmissionCategory $jalur_pendaftaran)
    {
        $documents = DocumentRequirement::all();
        $jalur_pendaftaran->load('documentRequirements');
        return view('admin.pmb.categories.edit', ['category' => $jalur_pendaftaran, 'documents' => $documents]);
    }

    public function update(Request $request, AdmissionCategory $jalur_pendaftaran)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admission_categories,name,' . $jalur_pendaftaran->id,
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'display_group' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'documents' => 'nullable|array',
            'documents.*' => 'exists:document_requirements,id',
        ]);

        $jalur_pendaftaran->update($validated);

        // Sync documents (handle empty selection by defaulting to empty array)
        $jalur_pendaftaran->documentRequirements()->sync($request->input('documents', []));

        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil diperbarui.');
    }

    public function destroy(AdmissionCategory $jalur_pendaftaran)
    {
        $jalur_pendaftaran->delete();
        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil dihapus.');
    }
}
