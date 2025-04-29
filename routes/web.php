<?php

use App\Http\Controllers\Api\ApiQueueController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        // 'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    Route::resource('service', ServiceController::class);
    Route::post('/counter/call', [CounterController::class, 'callNext'])->name('counter.call');

    Route::resource('counter', CounterController::class)->except(['index']);
    Route::get('/counter', [CounterController::class, 'index'])->name('counter.panel');
    Route::post('/counter/call', [CounterController::class, 'callNext'])->name('counter.call');

    Route::get('/queue', [QueueController::class, 'index'])->name('queue');
    Route::get('/queue/create', [QueueController::class, 'create'])->name('queue.create');
    Route::get('/queue/{queue}', [QueueController::class, 'show'])->name('queue.show');
    Route::post('/queue', [QueueController::class, 'store'])->name('queue.store');
    Route::post('/queue/{queueId}/finish', [QueueController::class, 'finish'])->name('queue.finish');
    Route::post('/queue/{queueId}/skip', [QueueController::class, 'skip'])->name('queue.skip');
    Route::get('/queue/call/{counterId}', [QueueController::class, 'callNext'])->name('queue.call');
});

Route::get('display', [DisplayController::class, 'index'])->name('display');
Route::get('display/waiting', [DisplayController::class, 'waiting'])->name('display.waiting');
Route::get('display/calling', [DisplayController::class, 'calling'])->name('display.calling');

Route::get('ticket', [TicketController::class, 'index'])->name('ticket');
Route::post('ticket', [TicketController::class, 'store'])->name('ticket.store');

Route::post('/api/queues', [ApiQueueController::class, 'createQueue'])->name('createQueue');
Route::get('/api/queue/next-call', [ApiQueueController::class, 'nextCall'])->name('next-call');
Route::get('/api/queue/recent-call', [ApiQueueController::class, 'recentCalls'])->name('recent-call');
