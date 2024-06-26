<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/', [ChatController::class, 'index'])->name('index');
Route::get('/progress', [ChatController::class, 'progress'])->name('progress');
Route::get('/result', [ChatController::class, 'result'])->name('result');
Route::get('/history', [ChatController::class, 'history'])->name('history');
Route::post('/analyze', [ChatController::class, 'analyze'])->name('analyze');
Route::get('/admin/history', [ChatController::class, 'adminHistory'])->name('admin.history');
Route::post('/save-consultation', [ChatController::class, 'saveConsultation']);
