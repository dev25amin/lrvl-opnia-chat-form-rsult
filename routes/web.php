<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextRequestController;
use App\Http\Controllers\ChatController;

Route::get('/', [TextRequestController::class, 'create'])->name('text.create');
Route::post('/store', [TextRequestController::class, 'store'])->name('text.store');
Route::get('/result/{id}', [TextRequestController::class, 'show'])->name('text.show');
Route::get('/api/status/{id}', [TextRequestController::class, 'checkStatus'])->name('text.status');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/check/{id}', [ChatController::class, 'checkMessage'])->name('chat.check');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/clear', [ChatController::class, 'clearChat'])->name('chat.clear');