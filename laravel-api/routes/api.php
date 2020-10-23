<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;

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



Route::post('user/show', [UserController::class, 'login'])->name('login');
Route::post('user/store', [UserController::class, 'create'])->name('user_create');
Route::middleware('auth:api')->post('new_move/{user_id}/{game_id}', [GameController::class, 'new_move'])->name('new_move');
Route::middleware('auth:api')->post('start_game/{user_id}/{game_id}', [GameController::class, 'start_game'])->name('start_game');
Route::middleware('auth:api')->post('game/store', [GameController::class, 'store'])->name('game');
Route::get('game/{user_id}/{game_id}', [GameController::class, 'show'])->name('show');
Route::middleware('auth:api')->post('game/{game}/solve', [GameController::class, 'update'])->name('update');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
