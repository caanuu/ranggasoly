<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        
        $leaves = Leave::with('employee')->get();
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'keterangan'  => 'nullable|string',
        ]);


        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);

        $leaveData = [
            'employee_id' => $validated['employee_id'],
            'tanggal_mulai'     => $validated['start_date'],
            'tanggal_selesai'     => $validated['end_date'],
            'keterangan'      => $validated['keterangan'],
        ];

        Leave::create($leaveData);

        // looping setiap tanggal
        while ($start->lte($end)) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $validated['employee_id'],
                    'tanggal'     => $start->toDateString(),
                ],
                [
                    'status'      => 'cuti',
                ]
            );
            $start->addDay();
        }   

        return redirect()->route('leaves.index')
         ->with('success', 'Cuti berhasil ditambahkan.');
    }


    
    public function updateStatus(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak'
        ]);
        $leave->status = $request->status;
        $leave->save();

        return back()->with('success', 'Status cuti berhasil diperbarui.');
    }
}