<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
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

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// ITEMS
// Route::post('/items/store', [ItemController::class, 'store'])->name('item.store')->middleware('auth');
Route::post('/items/store', [ItemController::class, 'store'])->name('item.store');
Route::get('/items', [ItemController::class, 'index'])->name('item.index');
// Route::put('/vehicle-types/{id}', [VehicleTypeController::class, 'update'])->name('vehicle-types.update')->middleware('auth');
Route::put('/item/{id}', [ItemController::class, 'update'])->name('item.update');

// LOCATIONS
Route::post('/locations/store', [LocationController::class, 'store'])->name('location.store');
Route::get('/locations', [LocationController::class, 'index'])->name('location.index');

// CUSTOMERS
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customer.store');

// USERS
Route::post('/users/store', [UserController::class, 'store'])->name('user.store');
Route::get('/users', [UserController::class, 'index'])->name('user.index');