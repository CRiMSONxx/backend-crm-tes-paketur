<?php
use App\Http\Controllers\Company\Auth\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Api\ComApiController;
use App\Http\Controllers\Api\ComUsersApiController;

Route::post('company_login', [JWTAuthController::class, 'login'])->name('jwt.login_company');

Route::middleware(['auth:api'])->prefix('auth')->group(function () {;
    Route::post('me', [JWTAuthController::class, 'me'])->name('me');
    Route::get('user', [JWTAuthController::class, 'getUser']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
});

Route::middleware(['auth:api'])->prefix('company')->group(function () {
    Route::post('company_register', [ComApiController::class, 'store'])->name('api.company.register_company');
    Route::get('/all', [ComApiController::class, 'index'])->name('api.company.list');
    Route::get('/id/{id}', [ComApiController::class, 'show'])->name('api.company.view');
    Route::get('/id/{id}/employee', [ComApiController::class, 'show_employee'])->name('api.company.view_employee');
    //employee/manager
    Route::get('/employee', [ComUsersApiController::class, 'index'])->name('api.employee.index');
    Route::get('/employee/{id}', [ComUsersApiController::class, 'show'])->name('api.employee.view');
    Route::post('/employee', [ComUsersApiController::class, 'store'])->name('api.employee.store');
    Route::patch('/employee/{id}', [ComUsersApiController::class, 'update'])->name('api.employee.update');
    Route::delete('/employee/{id}', [ComUsersApiController::class, 'destroy'])->name('api.employee.destroy');
    Route::patch('/employee/reactivate/{id}', [ComUsersApiController::class, 'reactivate'])->name('api.employee.reactivate');
    // Route::get('/manager', [ComUsersApiController::class, 'edit'])->name('api.manager.edit');
    // Route::patch('/manager', [ComUsersApiController::class, 'update'])->name('api.manager.update');
    // Route::delete('/manager', [ComUsersApiController::class, 'destroy'])->name('api.manager.destroy');
});