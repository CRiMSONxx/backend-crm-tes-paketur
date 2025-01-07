<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Company\ManagerController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\CompanyUsersController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth:web')->prefix('controlpanel')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth:web', 'verified'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/company/register', [ManagerController::class, 'create'])->name('company.register');
    Route::get('/company', [CompanyController::class, 'index'])->name('company.list');
    Route::get('/employee', [CompanyUsersController::class, 'index'])->name('employee.list');
    Route::get('/employee/{id}', [CompanyUsersController::class, 'edit'])->name('employee.view');
});

//company start
Route::get('/company/login', [ManagerController::class, 'login'])->name('company.login')->middleware('guest');
Route::post('company_register', [\App\Http\Controllers\Company\Auth\JWTAuthController::class, 'register'])->name('jwt.register_company')->middleware('auth:web'); // must from controlpanel
Route::middleware(['auth:api'])->prefix('company')->group(function () {
    Route::get('/manager', [ManagerController::class, 'edit'])->name('manager.edit');
    Route::patch('/manager', [ManagerController::class, 'update'])->name('manager.update');
    Route::delete('/manager', [ManagerController::class, 'destroy'])->name('manager.destroy');
    Route::get('/employee', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::patch('/employee', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee', [EmployeeController::class, 'destroy'])->name('employee.destroy');
});

require __DIR__.'/auth.php';