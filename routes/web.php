<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SocialiteAuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

//Bienvenida
Route::get('/', function(){
    return view('welcome');
})->name('welcome');
//Rutas de autenticación
Route::get('auth/github/redirect', [SocialiteAuthController::class, 'index']);
Route::get('auth/github/callback', [SocialiteAuthController::class, 'store']);

//Rutas para crear post y mostrar post
Volt::route('/dashboard', 'posts.index');

Route::middleware('auth')->group( function () {
    Volt::route('/posts/create', 'posts.create');
    Volt::route('/posts/{post}/edit', 'posts.edit');
    Volt::route('/profile', 'profile.index');
});


Volt::route('/posts/{post}', 'posts.show');
Volt::route('/singof', 'users.signof');

//Ruta para cerrar sesión(falta corregir ya que no se encutra la ruta)
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
