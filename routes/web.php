<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//auth
Route::get('login',[AuthController::class, 'index'])->name('login');
Route::post('login',[AuthController::class, 'login']);
Route::get('logout',[AuthController::class, 'logout']);

//kategori
Route::get('/kategori', [CategoryController::class, 'list']);
route::post('/kategori',[CategoryController::class,'store']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
