<?php


namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('attendances.create');
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $label = '';
        $dates = [];
        $weeks = [];
        $months = [];

        if ($filter == 'month') {
            $month = $request->get('month', now()->format('Y-m'));
            $start = Carbon::parse($month . '-01');
            $end = $start->copy()->endOfMonth();
            $label = $start->translatedFormat('F Y');

            // Bagi bulan menjadi minggu-minggu (Senin-Sabtu)
            $period = CarbonPeriod::create($start, $end);
            $week = [];
            foreach ($period as $date) {
                if ($date->dayOfWeek != Carbon::SUNDAY) {
                    $week[] = $date->toDateString();
                }
                // Jika Sabtu atau tanggal terakhir, simpan minggu
                if ($date->dayOfWeek == Carbon::SATURDAY || $date->equalTo($end)) {
                    if ($week) {
                        $weeks[] = [
                            'start' => Carbon::parse($week[0]),
                            'end' => Carbon::parse(end($week)),
                            'dates' => $week
                        ];
                        $week = [];
                    }
                }
            }
            // Ambil semua tanggal dalam bulan (tanpa Minggu) untuk eager load
            $dates = [];
            foreach ($weeks as $w) {
                $dates = array_merge($dates, $w['dates']);
            }
            $dates = array_unique($dates);

        } elseif ($filter == 'year') {
            $year = $request->get('year', now()->year);
            $start = Carbon::create($year, 1, 1);
            $end = Carbon::create($year, 12, 31);
            $label = 'Tahun ' . $year;

            // Bagi tahun menjadi bulan-bulan
            for ($m = 1; $m <= 12; $m++) {
                $mStart = Carbon::create($year, $m, 1);
                $mEnd = $mStart->copy()->endOfMonth();
                $period = CarbonPeriod::create($mStart, $mEnd);
                $monthDates = [];
                foreach ($period as $date) {
                    if ($date->dayOfWeek != Carbon::SUNDAY) {
                        $monthDates[] = $date->toDateString();
                    }
                }
                $months[] = [
                    'label' => $mStart->translatedFormat('M'),
                    'dates' => $monthDates
                ];
                $dates = array_merge($dates, $monthDates);
            }
            $dates = array_unique($dates);

        } else { // week
            $start = Carbon::now()->startOfWeek(); // Senin
            $end = $start->copy()->addDays(5); // Sabtu
            $label = 'Minggu Ini';

            $period = CarbonPeriod::create($start, $end);
            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->toDateString();
            }
        }

        if ($filter == 'week') {
            $start = $request->get('start')
                ? Carbon::parse($request->get('start'))
                : Carbon::now()->startOfWeek();
            $end = $start->copy()->addDays(5); // Senin-Sabtu
            $label = 'Minggu Ini';
            $period = CarbonPeriod::create($start, $end);
            $dates = [];
            foreach ($period as $date) {
                $dates[] = $date->toDateString();
            }
        }

        // Eager load attendances hanya pada tanggal yang diperlukan
        $employees = Employee::with(['attendances' => function($q) use ($dates) {
            $q->whereIn('tanggal', $dates);
        }])->get();

        return view('attendances.index', compact('employees', 'dates', 'label', 'weeks', 'months'));
    }

    public function searchEmployee(Request $request)
    {
        $q = $request->q;
        return Employee::where('name', 'like', "%$q%")
            ->orWhere('nomor_pegawai', 'like', "%$q%")
            ->limit(10)
            ->get(['id','name','nomor_pegawai']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal'     => 'required|date',
            'foto_bukti'  => 'nullable|image|max:2048',
            'status'      => 'required|in:hadir,terlambat,izin,sakit,cuti',
        ]);

        $attendanceData = [
            'employee_id' => $validated['employee_id'],
            'tanggal'     => $validated['tanggal'],
            'status'      => $validated['status'],
        ];

        if ($request->hasFile('foto_bukti')) {
            $attendanceData['foto_bukti'] = $request->file('foto_bukti')->store('bukti_kehadiran', 'public');
        }

        $attendance = Attendance::create($attendanceData);

        $employee = $attendance->employee;
        $tanggal = Carbon::parse($attendance->tanggal)->translatedFormat('l, d F Y');
        
        $activityText = "{$employee->nama} {$attendance->status} pada hari {$tanggal}";

        ActivityLog::create([
            'employee_id' => $employee->id,
            'activity' => $activityText,
        ]);


        return redirect()->route('attendances.index')->with('success', 'Kehadiran berhasil ditambahkan.');
    }
}