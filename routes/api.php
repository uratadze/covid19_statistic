<?php

use App\Http\Controllers\CovidController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('/countryData', [CovidController::class, 'countryData'])->name('country.data');
    Route::post('/statisticSummary', [CovidController::class, 'statisticSummary'])->name('country.statistics');
});

Route::post('/register', [UserController::class, 'register'])->name('user.registration');
Route::post('/login', [UserController::class, 'login'])->name('user.login');


