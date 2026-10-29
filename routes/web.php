<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', [AttendanceController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('dashboard.index');
Route::get('/reports/in-out', [AttendanceController::class, 'inOutReport'])->name('reports.inout');
Route::get('/reports/working-hours', [AttendanceController::class, 'workingHoursReport'])->name('reports.hours');
Route::get('/employees', [AttendanceController::class, 'employees'])->name('employees.index');
Route::get('/api/employee-suggestions', [AttendanceController::class, 'suggestions'])->name('api.suggestions');
Route::get('/login', function() { return view('attendance.login'); })->name('login');
Route::get('/settings', function() { return redirect()->route('dashboard'); })->name('settings');
