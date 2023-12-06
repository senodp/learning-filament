<?php

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

Livewire::setUpdateRoute(function ($handle) {
return Route::post('/learning-filament/public/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
return Route::get('/learning-filament/public/livewire/livewire.js', $handle);
});