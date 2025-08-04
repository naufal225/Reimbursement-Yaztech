<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'index'])->name('employee.dashboard');
    Route::get('/employee/reimbursement', fn() => view('employee.reimbursement'))->name('employee.reimbursement');
    Route::get('/employee/history', fn() => view('employee.history'))->name('employee.history');
});

// Approver Pages
Route::middleware(['auth', 'role:approver'])->group(function () {
    Route::get('/approver/dashboard', fn() => view('approver.dashboard'))->name('approver.dashboard');
    Route::get('/approver/reimbursements', fn() => view('approver.list'))->name('approver.reimbursements');
    Route::get('/approver/reimbursements/{id}', fn() => view('approver.detail'))->name('approver.detail');
});
