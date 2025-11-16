<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month'); // default: bulan
        $label = '';
        $dates = [];
        $weeks = [];
        $months = [];

        // --- Filter Mingguan ---
        if ($filter == 'week') {
            // ... (logika filter Anda sudah benar) ...
            $start = $request->get('start')
                ? Carbon::parse($request->get('start'))
                : Carbon::now()->startOfWeek(Carbon::MONDAY);

            $end = $start->copy()->addDays(5); // Senin - Sabtu
            $label = 'Minggu ' . $start->format('d M') . ' - ' . $end->format('d M Y');

            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $dates[] = $date->toDateString();
            }

            // --- Filter Bulanan ---
        } elseif ($filter == 'month') {
            // ... (logika filter Anda sudah benar) ...
            $month = $request->get('month', now()->month);
            $year  = $request->get('year', now()->year);

            $start = Carbon::create($year, $month, 1);
            $end   = $start->copy()->endOfMonth();
            $label = $start->translatedFormat('F Y');

            $period = CarbonPeriod::create($start, $end);
            $week = [];
            foreach ($period as $date) {
                if ($date->dayOfWeek != Carbon::SUNDAY) {
                    $week[] = $date->toDateString();
                }
                if ($date->dayOfWeek == Carbon::SATURDAY || $date->equalTo($end)) {
                    if ($week) {
                        $weeks[] = [
                            'start' => Carbon::parse($week[0]),
                            'end'   => Carbon::parse(end($week)),
                            'dates' => $week,
                        ];
                        $dates = array_merge($dates, $week);
                        $week = [];
                    }
                }
            }

            // --- Filter Tahunan ---
        } elseif ($filter == 'year') {
            // ... (logika filter Anda sudah benar) ...
            $year = $request->get('year', now()->year);
            $label = 'Tahun ' . $year;

            for ($m = 1; $m <= 12; $m++) {
                $mStart = Carbon::create($year, $m, 1);
                $mEnd   = $mStart->copy()->endOfMonth();
                $period = CarbonPeriod::create($mStart, $mEnd);

                $monthDates = [];
                foreach ($period as $date) {
                    if ($date->dayOfWeek != Carbon::SUNDAY) {
                        $monthDates[] = $date->toDateString();
                    }
                }
                $months[] = [
                    'label' => $mStart->translatedFormat('M'),
                    'dates' => $monthDates,
                ];
                $dates = array_merge($dates, $monthDates);
            }
        }

        // --- Ambil data karyawan + absensi ---
        $employees = Employee::with(['attendances' => function ($q) use ($dates) {
            $q->whereIn('tanggal', $dates);
        }])->get();

        // [DIPERBAIKI] Menggunakan firstOrCreate untuk menghindari error 'null'
        $setting = Salary::firstOrCreate([]);

        // --- Hitung statistik per karyawan ---
        $reportData = $employees->map(function ($employee) use ($dates, $setting) {
            $count = [
                'hadir'        => 0,
                'izin'         => 0,
                'sakit'        => 0,
                'cuti'         => 0,
                'tidak_hadir'  => 0, // <-- Tambah
                'kosong'       => 0,
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

            // [DIPERBAIKI] Logika gaji disesuaikan dengan database
            // 'terlambat', 'izin', 'cuti', 'tidak_hadir' digabung dan dikali absent_rate
            $nonHadir = $count['izin'] + $count['cuti'] + $count['tidak_hadir']; // <-- Tambah

            $totalSalary = ($count['hadir'] * $setting->present_rate) +
                ($count['sakit'] * $setting->sick_rate) +
                ($nonHadir * $setting->absent_rate);
            // 'kosong' tidak dihitung

            return [
                'employee'    => $employee,
                ...$count,
                'totalSalary' => $totalSalary,
            ];
        });

        // --- Statistik agregat ---
        $salarycost = $reportData->sum('totalSalary');
        $totalCutiPegawai = Attendance::where('status', 'cuti')
            ->whereIn('tanggal', $dates)
            ->distinct('employee_id')
            ->count('employee_id');

        return view('report.index', compact(
            'reportData',
            'dates',
            'label',
            'weeks',
            'months',
            'salarycost',
            'totalCutiPegawai'
        ));
    }
}
