<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GalleryController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::controller(LoginRegisterController::class)->group(function(){
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', 'users')->name('users');
    Route::post('/logout', 'logout');
    Route::get('/logout', 'logout')->name('logout');
});

Route::get('restricted', function () {
    return "Anda berusia lebih dari 18 tahun!";
})->middleware('checkage');

Route::get('admin', function () {
    return "Anda merupakan admin!";
})->middleware('admin');

Route::resource('users', UserController::class);

Route::resource('edit', UserController::class);

Route::resource('gallery', GalleryController::class);

Route::resource('edit', GalleryController::class);