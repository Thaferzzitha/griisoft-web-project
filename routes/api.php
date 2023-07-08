<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

//Graphic Routes
Route::middleware('auth:sanctum')->get('/graphic/list', [GraphicController::class, 'list']);
Route::middleware('auth:sanctum')->get('/graphic/{graphic}', [GraphicController::class, 'show']);
Route::middleware('auth:sanctum')->apiResource('/graphic', GraphicController::class);
Route::middleware('auth:sanctum')->get('/user/{user}/make-admin', [UserController::class, 'makeSuperAdmin']);
Route::middleware('auth:sanctum')->get('/user/{user}/remove-admin', [UserController::class, 'removeSuperAdmin']);
Route::middleware('auth:sanctum')->get('/user/list-users', [UserController::class, 'listAllUsers']);
