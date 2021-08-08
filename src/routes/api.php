<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register']);

Route::get('/task', [TaskController::class, 'get'])->middleware('auth:api');;
Route::get('/task/{category_id}', [TaskController::class, 'getTaskByCategory'])->middleware('auth:api');;
Route::put('/task/status', [TaskController::class, 'setStatus'])->middleware('auth:api');;
Route::get('/task/change/{id}', [TaskController::class, 'change'])->middleware('auth:api');;