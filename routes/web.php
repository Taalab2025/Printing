<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

// Home page route using HomeController
Route::get('/', [HomeController::class, 'index']);

// Add a test route to verify basic functionality
Route::get('/test', function () {
    return 'The application is working correctly!';
});

// Basic auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Basic category routes
Route::get('/categories', function () {
    return 'Categories page';
})->name('categories');

Route::get('/categories/{id}', function ($id) {
    return 'Category ' . $id . ' details';
})->name('categories.show');

// Basic vendor routes
Route::get('/vendors', function () {
    return 'Vendors page';
})->name('vendors');

Route::get('/vendors/{id}', function ($id) {
    return 'Vendor ' . $id . ' details';
})->name('vendors.show');

// Basic service routes
Route::get('/services', function () {
    return 'Services page';
})->name('services');

Route::get('/services/{id}', function ($id) {
    return 'Service ' . $id . ' details';
})->name('services.show');

// Vendor registration route
Route::get('/vendor/register', function () {
    return 'Vendor registration page';
})->name('vendor.register');
