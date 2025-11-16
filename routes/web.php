<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
  use App\Http\Controllers\RegisterController;


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
// Route pour inscription
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [LoginController::class, 'dashboard'])->middleware('auth')->name('dashboard');
use App\Http\Controllers\FactureController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FactureController::class, 'index'])->name('dashboard');
    Route::post('/factures/{facture}/payer', [FactureController::class, 'payer'])->name('factures.payer');
    // callback KprimePay
    Route::post('/paiement/callback', [FactureController::class, 'callback'])->name('paiement.callback');
});
Route::get('/factures/{id}', [\App\Http\Controllers\FactureController::class, 'show'])
    ->name('factures.show');
