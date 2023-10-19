<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\LoginController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index')->middleware('auth');
Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');

// ITEMS
Route::post('/items/store', [ItemController::class, 'store'])->name('item.store')->middleware('auth');
Route::get('/items', [ItemController::class, 'index'])->name('item.index')->middleware('auth');
Route::put('/item/{id}', [ItemController::class, 'update'])->name('item.update')->middleware('auth');
Route::post('/categories/store', [ItemController::class, 'storecategory'])->name('item.storecategory')->middleware('auth');

// LOCATIONS
Route::post('/locations/store', [LocationController::class, 'store'])->name('location.store')->middleware('auth');
Route::get('/locations', [LocationController::class, 'index'])->name('location.index')->middleware('auth');

// CUSTOMERS
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customer.store')->middleware('auth');
Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index')->middleware('auth');
Route::get('/download-soa/{customerId}', [CustomerController::class, 'downloadSoa']);

// USERS
Route::post('/users/store', [UserController::class, 'store'])->name('user.store')->middleware('auth');
Route::get('/users', [UserController::class, 'index'])->name('user.index')->middleware('auth');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// POS
Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('auth');
Route::get('/search-items', [PosController::class, 'searchItems'])->name('pos.searchItems')->middleware('auth');
Route::get('/search-customers', [PosController::class, 'searchCustomers'])->name('pos.searchCustomers')->middleware('auth');
Route::get('/get-customer-credit', [PosController::class, 'getCustomerCredit'])->name('pos.getCustomerCredit')->middleware('auth');
Route::post('/save-order', [PosController::class, 'saveOrder'])->name('pos.saveOrder')->middleware('auth');
Route::get('/all-receivables', [PosController::class, 'allReceivables'])->name('pos.allReceivables')->middleware('auth');
Route::get('/customer-receivables', [PosController::class, 'customerReceivables'])->name('pos.customerReceivables')->middleware('auth');
Route::post('/receive-payment', [PosController::class, 'receivepayment'])->name('pos.receivepayment')->middleware('auth');
