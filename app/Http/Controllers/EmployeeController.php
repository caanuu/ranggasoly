<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- TAMBAHKAN INI

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('leaves')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function show(Employee $employee)
    {
        // [DIPERBARUI] Ambil data absensi DAN data cuti
        $attendances = $employee->attendances()->orderByDesc('tanggal')->get();
        $leaves = $employee->leaves()->orderByDesc('tanggal_mulai')->get();

        // [DIPERBARUI] Kirim kedua data ke view
        return view('employees.show', compact('employee', 'attendances', 'leaves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email',
            'nomor_pegawai' => 'required|string|unique:employees,nomor_pegawai',
            'penempatan'    => 'required|string|max:255',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }


    // --- [TAMBAHKAN METHOD BARU INI] ---
    /**
     * Tampilkan form untuk mengedit karyawan.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // --- [TAMBAHKAN METHOD BARU INI] ---
    /**
     * Update data karyawan di database.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                Rule::unique('employees')->ignore($employee->id), // Abaikan email saat ini
            ],
            'nomor_pegawai' => [
                'required',
                'string',
                Rule::unique('employees')->ignore($employee->id), // Abaikan NIK saat ini
            ],
            'penempatan'    => 'required|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // --- [TAMBAHKAN METHOD BARU INI] ---
    /**
     * Hapus karyawan dari database.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani jika ada error foreign key (misal jika data terkait tidak di-set cascade)
            return redirect()->route('employees.index')->with('error', 'Gagal menghapus data karyawan. Pastikan data terkait sudah dihapus.');
        }
    }
}
