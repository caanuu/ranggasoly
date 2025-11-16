<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // <-- TAMBAHKAN DI SINI

class SalaryController extends Controller
{

    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // [DIPERBAIKI]
        $setting = Salary::firstOrCreate([]);

        // generate semua hari kerja dalam bulan tsb (kecuali Minggu)
        $dates = collect(
            range(1, Carbon::create($year, $month)->daysInMonth)
        )->map(fn($d) => Carbon::create($year, $month, $d))
            ->reject(fn($date) => $date->isSunday())
            ->map->toDateString();

        $employees = Employee::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }])->get()->map(function ($employee) use ($dates, $setting) {

            $count = [
                'hadir'       => 0,
                'sakit'       => 0,
                'cuti'        => 0,
                'izin'        => 0,
                'tidak_hadir' => 0, // <-- TAMBAHKAN INI
                'kosong'      => 0, // <-- TAMBAHKAN INI
            ];

            foreach ($dates as $date) {
                $att = $employee->attendances->firstWhere('tanggal', $date);
                if ($att) {
                    if ($att->status == 'hadir') {
                        $count['hadir']++;
                    } elseif ($att->status == 'sakit') {
                        $count['sakit']++;
                    } elseif ($att->status == 'izin') {
                        $count['izin']++;
                    } elseif ($att->status == 'cuti') {
                        $count['cuti']++;
                    } elseif ($att->status == 'tidak_hadir') {
                        $count['tidak_hadir']++;
                    } // <-- Tambah
                } else {
                    $count['kosong']++;
                }
            }

            // Gabungkan semua yang non-hadir
            $nonHadir = $count['izin'] + $count['cuti'] + $count['tidak_hadir'];
            $totalSalary = ($count['hadir'] * $setting->present_rate) +
                ($count['sakit'] * $setting->sick_rate) +
                ($nonHadir * $setting->absent_rate); // <-- Gunakan $nonHadir

            return [
                'employee' => $employee,
                ...$count, // 'hadir', 'sakit', 'tidak_hadir', 'kosong'
                'totalSalary' => $totalSalary,
            ];
        });

        // [DIPERBAIKI] Hanya hitung total gaji dari karyawan yang pernah 'hadir'
        $salarycost = $employees->filter(function ($data) {
            return $data['hadir'] > 0;
        })->sum('totalSalary');

        return view('salary.index', compact('employees', 'month', 'year', 'salarycost'));
    }

    public function edit()
    {
        $setting = Salary::first() ?? new Salary();
        return view('salary.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'present_rate' => 'required|integer',
            'sick_rate'    => 'required|integer',
            'absent_rate'  => 'required|integer',
        ]);

        // Logika ini sudah benar, akan update atau create
        $setting = Salary::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            Salary::create($validated);
        }

        return redirect()->route('salary.edit')->with('success', 'Tarif gaji berhasil diperbarui.');
    }

    public function print($id, Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // [DIPERBAIKI]
        $setting = Salary::firstOrCreate([]);

        $dates = collect(
            range(1, Carbon::create($year, $month)->daysInMonth)
        )->map(fn($d) => Carbon::create($year, $month, $d))
            ->reject(fn($date) => $date->isSunday())
            ->map->toDateString();

        $employee = Employee::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }])->findOrFail($id);

        $count = [
            'hadir'       => 0,
            'sakit'       => 0,
            'cuti'        => 0,
            'izin'        => 0,
            'tidak_hadir' => 0, // <-- Tambah
            'kosong'      => 0, // <-- Tambah
        ];

        foreach ($dates as $date) {
            $att = $employee->attendances->firstWhere('tanggal', $date);
            if ($att && isset($count[$att->status])) {
                $count[$att->status]++;
            }
            // kalau $att == null → di-skip
        }

        // [DIPERBAIKI] Logika gaji disesuaikan
        // 'izin', 'cuti', 'tidak_hadir' digabung
        $nonHadir = $count['izin'] + $count['cuti'] + $count['tidak_hadir'];

        $totalSalary = ($count['hadir'] * $setting->present_rate) +
            ($count['sakit'] * $setting->sick_rate) +
            ($nonHadir * $setting->absent_rate);

        return view('salary.print', compact('employee', 'count', 'totalSalary', 'month', 'year', 'setting'));
    }
    // --- [TAMBAHKAN METHOD BARU INI] ---
    public function downloadPDF($id, Request $request)
    {
        // 1. Ambil semua data yang sama persis dengan method print()
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $setting = Salary::firstOrCreate([]);

        $dates = collect(
            range(1, Carbon::create($year, $month)->daysInMonth)
        )->map(fn($d) => Carbon::create($year, $month, $d))
            ->reject(fn($date) => $date->isSunday())
            ->map->toDateString();

        $employee = Employee::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }])->findOrFail($id);

        $count = [
            'hadir'       => 0,
            'sakit'       => 0,
            'cuti'        => 0,
            'izin'        => 0,
            'tidak_hadir' => 0,
            'kosong'      => 0, // <-- TAMBAHKAN INI
        ];

        foreach ($dates as $date) {
            $att = $employee->attendances->firstWhere('tanggal', $date);
            if ($att) {
                if ($att->status == 'hadir') {
                    $count['hadir']++;
                } elseif ($att->status == 'sakit') {
                    $count['sakit']++;
                } elseif ($att->status == 'izin') {
                    $count['izin']++;
                } elseif ($att->status == 'cuti') {
                    $count['cuti']++;
                } elseif ($att->status == 'tidak_hadir') {
                    $count['tidak_hadir']++;
                }
            } else {
                $count['kosong']++; // <-- TAMBAHKAN BLOK ELSE
            }
        }

        $nonHadir = $count['izin'] + $count['cuti'] + $count['tidak_hadir'];
        $totalSalary = ($count['hadir'] * $setting->present_rate) +
            ($count['sakit'] * $setting->sick_rate) +
            ($nonHadir * $setting->absent_rate);

        // 2. Load view 'salary.print' dengan data yang ada
        $pdf = Pdf::loadView('salary.print', compact(
            'employee',
            'count',
            'totalSalary',
            'month',
            'year',
            'setting'
        ));

        // 3. Buat nama file dinamis
        $fileName = "slip-gaji-{$employee->name}-{$month}-{$year}.pdf";

        // 4. Download PDF-nya
        return $pdf->download($fileName);
    }
}
