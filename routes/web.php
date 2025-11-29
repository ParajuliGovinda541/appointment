<?php

use App\Http\Controllers\Web\Post\PostController;
use App\Http\Controllers\Web\Visitor\VisitiorController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('posts', PostController::class);
Route::put('/{id}/activate', [PostController::class, 'activate'])->name('posts.activate');
Route::put('/{id}/deactivate', [PostController::class, 'deactivate'])->name('posts.deactivate');

Route::resource('visitors', VisitiorController::class);
Route::put('/{id}/activate', [VisitiorController::class, 'activate'])->name('visitors.activate');
Route::put('/{id}/deactivate', [VisitiorController::class, 'deactivate'])->name('visitors.deactivate');
