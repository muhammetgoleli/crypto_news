<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\CryptoNewsController;

Route::get('/', [CryptoNewsController::class, 'index']);
Route::get('/news', [CryptoNewsController::class, 'getNews'])->name('news');
Route::get('/coins', [CryptoNewsController::class, 'coins'])->name('coins');
