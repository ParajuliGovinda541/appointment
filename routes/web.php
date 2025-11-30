<?php

use App\Http\Controllers\Web\Officer\OfficerController;
use App\Http\Controllers\Web\Post\PostController;
use App\Http\Controllers\Web\Visitor\VisitiorController;
use App\Http\Controllers\Web\WorkDay\WorkDayController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/{post}', [PostController::class, 'update'])->name('update');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');

    Route::put('/{post}/activate', [PostController::class, 'activate'])->name('activate');
    Route::put('/{post}/deactivate', [PostController::class, 'deactivate'])->name('deactivate');
});
Route::prefix('visitors')->name('visitors.')->group(function () {
    Route::get('/', [VisitiorController::class, 'index'])->name('index');
    Route::get('/create', [VisitiorController::class, 'create'])->name('create');
    Route::post('/', [VisitiorController::class, 'store'])->name('store');
    Route::get('/{visitor}/edit', [VisitiorController::class, 'edit'])->name('edit');
    Route::put('/{visitor}', [VisitiorController::class, 'update'])->name('update');
    Route::delete('/{visitor}', [VisitiorController::class, 'destroy'])->name('destroy');

    Route::put('/{visitor}/activate', [VisitiorController::class, 'activate'])->name('activate');
    Route::put('/{visitor}/deactivate', [VisitiorController::class, 'deactivate'])->name('deactivate');
});
Route::prefix('officers')->name('officers.')->group(function () {
    Route::get('/', [OfficerController::class, 'index'])->name('index');
    Route::get('/create', [OfficerController::class, 'create'])->name('create');
    Route::post('/', [OfficerController::class, 'store'])->name('store');
    Route::get('/{officer}/edit', [OfficerController::class, 'edit'])->name('edit');
    Route::put('/{officer}', [OfficerController::class, 'update'])->name('update');
    Route::delete('/{officer}', [OfficerController::class, 'destroy'])->name('destroy');
    Route::post('/{officer}/activate', [OfficerController::class, 'activate'])->name('activate');
    Route::post('/{officer}/deactivate', [OfficerController::class, 'deactivate'])->name('deactivate');
    Route::get('/{officer}/appointments', [OfficerController::class, 'appointments'])->name('appointments');
});

Route::prefix('workdays')->name('workdays.')->group(function () {
    Route::get('/', [WorkDayController::class, 'index'])->name('index');
    Route::get('/create', [WorkDayController::class, 'create'])->name('create');
    Route::post('/', [WorkDayController::class, 'store'])->name('store');
    Route::get('/{workday}/edit', [WorkDayController::class, 'edit'])->name('edit');
    Route::put('/{workday}', [WorkDayController::class, 'update'])->name('update');
    Route::delete('/{workday}', [WorkDayController::class, 'destroy'])->name('destroy');
});
