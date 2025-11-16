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
                'hadir' => 0,
                'sakit' => 0,
                'tidak_hadir' => 0, // 'izin', 'cuti', 'terlambat' akan masuk sini
                'kosong' => 0,
            ];

            foreach ($dates as $date) {
                $att = $employee->attendances->firstWhere('tanggal', $date);
                if ($att) {
                    // [DIPERBAIKI] Logika disederhanakan
                    if ($att->status == 'hadir') {
                        $count['hadir']++;
                    } elseif ($att->status == 'sakit') {
                        $count['sakit']++;
                    } else {
                        $count['tidak_hadir']++;
                    }
                } else {
                    $count['kosong']++;
                }
            }

            // [DIPERBAIKI] Perhitungan gaji disesuaikan
            $totalSalary = ($count['hadir'] * $setting->present_rate) +
                ($count['sakit'] * $setting->sick_rate) +
                ($count['tidak_hadir'] * $setting->absent_rate);
            // kosong tidak dihitung

            return [
                'employee' => $employee,
                ...$count, // 'hadir', 'sakit', 'tidak_hadir', 'kosong'
                'totalSalary' => $totalSalary,
            ];
        });

        $salarycost = $employees->sum('totalSalary');

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
            'hadir' => 0,
            'sakit' => 0,
            'cuti' => 0,
            'izin' => 0,
            'terlambat' => 0, // Ditambahkan untuk penampung
            'tidak_hadir' => 0,
        ];

        foreach ($dates as $date) {
            $att = $employee->attendances->firstWhere('tanggal', $date);
            if ($att && isset($count[$att->status])) {
                $count[$att->status]++;
            }
            // kalau $att == null → di-skip
        }

        // [DIPERBAIKI] Logika gaji disesuaikan
        // 'izin', 'cuti', 'terlambat', 'tidak_hadir' digabung
        $nonHadir = $count['izin'] + $count['cuti'] + $count['terlambat'] + $count['tidak_hadir'];

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
            'hadir' => 0,
            'sakit' => 0,
            'cuti' => 0,
            'izin' => 0,
            'terlambat' => 0,
            'tidak_hadir' => 0,
        ];

        foreach ($dates as $date) {
            $att = $employee->attendances->firstWhere('tanggal', $date);
            if ($att && isset($count[$att->status])) {
                $count[$att->status]++;
            }
        }

        $nonHadir = $count['izin'] + $count['cuti'] + $count['terlambat'] + $count['tidak_hadir'];
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
