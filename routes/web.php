<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SocialiteAuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', function(){
    return view('index');
})->name('index');


Route::get('auth/github/redirect', [SocialiteAuthController::class, 'index']);
Route::get('auth/github/callback', [SocialiteAuthController::class, 'store']);

Volt::route('/dashboard', 'dashboard');
Volt::route('/profile', 'profile.index');



//Ruta para cerrar sesión(falta corregir ya que no se encutra la ruta)
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
