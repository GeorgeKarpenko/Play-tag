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



Route::post('user/show', [UserController::class, 'show'])->name('user');
Route::post('user/store', [UserController::class, 'create'])->name('user_create');
Route::post('new_move/{user_id}/{game_id}', [GameController::class, 'new_move'])->name('new_move');
Route::post('start_game/{user_id}/{game_id}', [GameController::class, 'start_game'])->name('start_game');
Route::post('game/store', [GameController::class, 'store'])->name('game');
Route::get('game/{user_id}/{game_id}', [GameController::class, 'show'])->name('show');
Route::post('game/{game}/solve', [GameController::class, 'update'])->name('update');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
