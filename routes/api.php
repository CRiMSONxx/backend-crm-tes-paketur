<?php

use App\Http\Controllers\Company\Auth\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;

Route::post('company_register', [JWTAuthController::class, 'register'])->name('jwt.register_company')->middleware('auth:web');
Route::post('company_login', [JWTAuthController::class, 'login'])->name('jwt.login_company');

Route::middleware(['auth:api'])->prefix('auth')->group(function () {;
    Route::post('me', [JWTAuthController::class, 'me'])->name('me');
    Route::get('user', [JWTAuthController::class, 'getUser']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
});

Route::middleware(['auth:api'])->prefix('company')->group(function () {
    Route::get('/all', [CompanyController::class, 'index'])->name('api.company.list');
    Route::get('/employee', [CompanyUsersController::class, 'index'])->name('api.employee.list');
    Route::get('/employee/{id}', [CompanyUsersController::class, 'edit'])->name('api.employee.view');
    Route::get('/manager', [ManagerController::class, 'edit'])->name('api.manager.edit');
    Route::patch('/manager', [ManagerController::class, 'update'])->name('api.manager.update');
    Route::delete('/manager', [ManagerController::class, 'destroy'])->name('api.manager.destroy');
    Route::get('/employee', [EmployeeController::class, 'edit'])->name('api.employee.edit');
    Route::patch('/employee', [EmployeeController::class, 'update'])->name('api.employee.update');
    Route::delete('/employee', [EmployeeController::class, 'destroy'])->name('api.employee.destroy');
});