<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\NewsController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\TroubleReportController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/* AUTH API ROUTES */
Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

})->middleware([]);

/*INSTANCES API ROUTES*/
Route::middleware('level:4')->resource('instance', InstanceController::class);

/* USERS API ROUTES */
Route::group(['middleware' => 'level:4', 'prefix' => 'users'], function ($router) {
    /*
     * CRUD User Route
     */
    Route::middleware('level:4')->resource('', UserController::class);

    /*
     * Storing User
     */
    Route::post('store', [AuthController::class, 'store']);
})->middleware('level');

/* NEWS API ROUTES */ 
Route::middleware('level:4')->resource('news', NewsController::class);

/* PRESENCE API ROUTES */
Route::middleware('level:1')->resource('presence', PresenceController::class);

/* ACTIVITY API ROUTES */
Route::middleware('level:1')->resource('activity', ActivityController::class);

/* TROUBLE REPORT API ROUTES */
Route::middleware('level:1')->resource('troublereport', TroubleReportController::class);