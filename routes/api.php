<?php

use Illuminate\Support\Facades\Route;

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

require base_path('app/Modules/Auth/Routes/auth.php');
require base_path('app/Modules/User/Routes/user.php');
require base_path('app/Modules/Company/Routes/company.php');
require base_path('app/Modules/Category/Routes/category.php');


Route::middleware('auth:api')->get('/debug-token', [\App\Http\Controllers\DebugController::class, 'checkToken']);
