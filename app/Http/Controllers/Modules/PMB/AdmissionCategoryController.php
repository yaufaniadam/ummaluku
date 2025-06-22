<?php

namespace App\Http\Controllers\Modules\PMB;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCategory;
use Illuminate\Http\Request;

class AdmissionCategoryController extends Controller
{
    public function index()
    {
        $categories = AdmissionCategory::latest()->paginate(10);
        return view('admin.pmb.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pmb.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admission_categories,name',
            'description' => 'nullable|string',
            'display_group' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        AdmissionCategory::create($validated);

        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil ditambahkan.');
    }

    public function edit(AdmissionCategory $jalur_pendaftaran)
    {
        return view('admin.pmb.categories.edit', ['category' => $jalur_pendaftaran]);
    }

    public function update(Request $request, AdmissionCategory $jalur_pendaftaran)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:admission_categories,name,' . $jalur_pendaftaran->id,
            'description' => 'nullable|string',
            'display_group' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $jalur_pendaftaran->update($validated);

        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil diperbarui.');
    }

    public function destroy(AdmissionCategory $jalur_pendaftaran)
    {
        $jalur_pendaftaran->delete();
        return redirect()->route('admin.pmb.jalur-pendaftaran.index')->with('success', 'Jalur pendaftaran berhasil dihapus.');
    }
}