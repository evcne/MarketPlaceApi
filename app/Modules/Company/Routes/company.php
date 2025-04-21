<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Company\Controllers\CompanyController;
use App\Http\Middleware\RefreshJwtMiddleware;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::get('/{id}', [CompanyController::class, 'show']);
        Route::post('/', [CompanyController::class, 'store']);
        Route::put('/{id}', [CompanyController::class, 'update']);
        Route::put('/{id}/approved', [CompanyController::class, 'companyApprovedByAdmin']);
        //Route::delete('/{id}', [CompanyController::class, 'changeStatus']);
        Route::put('/{id}/change-status', [CompanyController::class, 'changeStatus']);

    });
});

