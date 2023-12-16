<?php

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InstanceController;

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

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/Siswa',[StudentController::class, 'getAllStudent']);
})->middleware([]);

//Instance
Route::group([
    'middleware' => ['api', 'level:4'],
    'prefix' => 'instance'
], function ($router) {
    Route::get('/GetData', [InstanceController::class, 'index']);
    Route::get('/Get/{identifier}', [InstanceController::class, 'show']);
    Route::post('/Insert', [InstanceController::class, 'store']);
    Route::put('/Update/{identifier}', [InstanceController::class, 'update']);
    Route::delete('/Delete/{identifier}', [InstanceController::class, 'destroy']);
})->middleware('level');

//USERS
Route::group([
    'middleware' => ['api', 'level:4'],
    'prefix' => 'users'
], function ($router) {
    Route::post('/StoreUser', [AuthController::class, 'storeUser']);
    Route::get('/GetAllUser', [UserController::class, 'getAllUser']);
    Route::get('/GetOneUser/{identifier}', [UserController::class, 'showOneUser']);
    Route::put('/UpdateUser/{identifier}', [UserController::class, 'updateUser']);
    Route::delete('/DeleteUser/{identifier}', [UserController::class, 'deleteUser']);
})->middleware('level');


