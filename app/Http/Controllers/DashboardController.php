<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Total Karyawan
        $totalEmployees = Employee::count();

        // Hadir tepat waktu hari ini
        $hadirTepatWaktu = Attendance::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        // Jumlah semua yang hadir (termasuk telat)
        $totalHadirHariIni = Attendance::whereDate('tanggal', $today)
            ->whereIn('status', ['hadir'])
            ->count();

        // Persentase kehadiran tepat waktu
        $attendancePercentage = $totalEmployees > 0
            ? round(($hadirTepatWaktu / $totalEmployees) * 100, 2)
            : 0;

        // Ambil aturan salary
        // [DIPERBAIKI] Menggunakan firstOrCreate untuk menghindari error 'null'
        // Ini akan mengambil baris pertama, atau membuat baris baru dengan default dari migrasi jika tabel kosong
        $setting = Salary::firstOrCreate([]);

        // Hitung total gaji bulan ini
        $totalSalary = 0;
        $employees = Employee::with(['attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }])->get();

        foreach ($employees as $employee) {
            $counts = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'cuti' => 0,
                'tidak_hadir' => 0 // <-- Tambah
            ];

            foreach ($employee->attendances as $att) {
                if ($att->status == 'hadir') {
                    $counts['hadir']++;
                } elseif ($att->status == 'sakit') {
                    $counts['sakit']++;
                } elseif ($att->status == 'izin') {
                    $counts['izin']++;
                } elseif ($att->status == 'cuti') {
                    $counts['cuti']++;
                } elseif ($att->status == 'tidak_hadir') {
                    $counts['tidak_hadir']++;
                } // <-- Tambah
            }

            $nonHadirCount = $counts['izin'] + $counts['cuti'] + $counts['tidak_hadir']; // <-- Tambah
            $totalSalary += ($counts['hadir'] * $setting->present_rate) +
                ($counts['sakit'] * $setting->sick_rate) +
                ($nonHadirCount * $setting->absent_rate);
        }

        // Cuti pending (misalnya status cuti = "pending")
        $karyawanCuti = Attendance::where('status', 'cuti')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->distinct('employee_id')
            ->count('employee_id');

        // Aktivitas terbaru (ambil dari attendance terbaru)
        $latestActivities = Attendance::with('employee')
            ->latest()
            ->take(5)
            ->get();

        $logs = ActivityLog::with('employee')->latest('created_at')->get();

        return view('dashboard', compact(
            'totalEmployees',
            'hadirTepatWaktu',
            'totalSalary',
            'karyawanCuti',
            'attendancePercentage',
            'latestActivities',
            'logs'
        ));
    }
}
