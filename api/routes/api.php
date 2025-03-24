<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Para autenticar as rotas adicione o middleware auth:sanctum. Não está adicionado pois o front não tem suporte para isso.
Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'list']);
    Route::get('/{id}', [\App\Http\Controllers\TaskController::class, 'findById']);
    Route::post('/', [\App\Http\Controllers\TaskController::class, 'create']);
    Route::put('/{id}', [\App\Http\Controllers\TaskController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\TaskController::class, 'delete']);
});

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');
