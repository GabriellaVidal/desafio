<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');

//Route::middleware('token')->group(function (){
Route::middleware('jwt.auth')->group(function (){
    Route::post('/pix', [\App\Http\Controllers\API\TransactionController::class, 'pix']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
