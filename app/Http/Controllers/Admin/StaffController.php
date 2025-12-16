<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Program;
use App\Models\WorkUnit;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;
use App\DataTables\StaffDataTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Imports\StaffImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StaffDataTable $dataTable)
    {
        return $dataTable->render('admin.sdm.staff.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('admin.sdm.staff.show', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::all();
        $workUnits = WorkUnit::all();
        $employmentStatuses = EmploymentStatus::all();
        return view('admin.sdm.staff.create', compact('programs', 'workUnits', 'employmentStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // User validation
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Staff validation
            'nip' => ['required', 'string', 'unique:staffs'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'unit_type' => ['required', 'in:prodi,bureau'], // To decide which foreign key to use
            'program_id' => ['nullable', 'required_if:unit_type,prodi', 'exists:programs,id'],
            'work_unit_id' => ['nullable', 'required_if:unit_type,bureau', 'exists:work_units,id'],
            'employment_status_id' => ['nullable', 'exists:employment_statuses,id'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('Tendik');

            // 2. Create Staff Linked to User
            Staff::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'program_id' => $request->unit_type === 'prodi' ? $request->program_id : null,
                'work_unit_id' => $request->unit_type === 'bureau' ? $request->work_unit_id : null,
                'employment_status_id' => $request->employment_status_id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
            ]);
        });

        return redirect()->route('admin.sdm.staff.index')
            ->with('success', 'Data staf berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff) // Using Route Model Binding for Staff
    {
        $programs = Program::all();
        $workUnits = WorkUnit::all();
        $employmentStatuses = EmploymentStatus::all();
        return view('admin.sdm.staff.edit', compact('staff', 'programs', 'workUnits', 'employmentStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            // User validation
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->user_id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // Staff validation
            'nip' => ['required', 'string', Rule::unique('staffs')->ignore($staff->id)],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'unit_type' => ['required', 'in:prodi,bureau'],
            'program_id' => ['nullable', 'required_if:unit_type,prodi', 'exists:programs,id'],
            'work_unit_id' => ['nullable', 'required_if:unit_type,bureau', 'exists:work_units,id'],
            'employment_status_id' => ['nullable', 'exists:employment_statuses,id'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($request, $staff) {
            // 1. Update User
            $user = $staff->user;
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Handle Profile Photo
            if ($request->hasFile('photo')) {
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path = $request->file('photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
            }

            $user->save();

            // 2. Update Staff
            $staff->update([
                'nip' => $request->nip,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'program_id' => $request->unit_type === 'prodi' ? $request->program_id : null,
                'work_unit_id' => $request->unit_type === 'bureau' ? $request->work_unit_id : null,
                'employment_status_id' => $request->employment_status_id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.sdm.staff.index')
            ->with('success', 'Data staf berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            $user->delete(); // Soft delete if trait exists, or force delete
        });

        return redirect()->route('admin.sdm.staff.index')
            ->with('success', 'Data staf berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new StaffImport, $request->file('import_file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                 $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->with('error', 'Gagal Import: ' . implode('<br>', $errorMessages));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('admin.sdm.staff.index')->with('success', 'Data tendik berhasil diimpor!');
    }
}
