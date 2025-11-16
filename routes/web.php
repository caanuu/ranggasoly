<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [AdminLoginController::class, 'login']);
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');


// Employee (Pegawai)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/karyawan', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/karyawan/tambah', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/karyawan', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/karyawan/{employee}', [EmployeeController::class, 'show'])->name('employees.show');

    // --- [TAMBAHKAN RUTE BARU INI] ---
    Route::get('/karyawan/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/karyawan/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/karyawan/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    // --- [AKHIR RUTE BARU] ---

    // Attendance (Kehadiran)
    Route::get('/karyawan/kehadiran/tambah', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::get('/kehadiran', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/api/employees/search', [AttendanceController::class, 'searchEmployee']);
    Route::post('/kehadiran', [AttendanceController::class, 'store'])->name('attendances.store');

    // Leave (Cuti)
    Route::get('/cuti', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/cuti/tambah', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/cuti', [LeaveController::class, 'store'])->name('leaves.store');
    Route::put('/cuti/{id}/status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');


    // Salary (Gaji)
    Route::get('/salary-set', [SalaryController::class, 'edit'])->name('salary.edit');
    // --- DIPERBAIKI: Rute ini sebelumnya '/salaies-set' ---
    Route::put('/salary-set', [SalaryController::class, 'update'])->name('salary.update');
    Route::get('/salaries', [SalaryController::class, 'index'])->name('salary.index');
    Route::get('/salary/{employee}/print', [SalaryController::class, 'print'])->name('salary.print');

    // --- [TAMBAHKAN RUTE BARU INI] ---
    Route::get('/salary/{employee}/download', [SalaryController::class, 'downloadPDF'])->name('salary.download');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');

    Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('search.live');
});

// Rute untuk Profil Admin
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
