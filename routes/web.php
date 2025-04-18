<?php

use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/queue', [QueueController::class, 'create'])->name('queue.create');
Route::post('/queue', [QueueController::class, 'store'])->name('queue.store');
