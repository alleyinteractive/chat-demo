<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [MessageController::class, 'index'])->name('dashboard');

Route::get('login/google', [LoginController::class, 'redirectToProvider'])->name('google.login');
Route::get('login/google/callback', [LoginController::class, 'handleProviderCallback']);
