<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::put('/data', [DataController::class, 'data']);
Route::get('/data', [DataController::class, 'data']);
Route::delete('/data', [DataController::class, 'data']);
Route::get('/jobs', [JobController::class, 'listJobs']);
