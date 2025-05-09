<?php

use App\Http\Controller;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', [FileUploadController::class, 'index'])->name('index');
Route::get('/upload/status', [FileUploadController::class, 'update'])->name('update.table');

Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload.file');