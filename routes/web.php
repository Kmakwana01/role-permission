<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','verified',RoleMiddleware::class.':user'])->group(function(){
    Route::get('/userRoute',function(){
        return 'userDashboard';
    });
});

Route::middleware(['auth','verified',RoleMiddleware::class.':admin'])->group(function(){
    Route::get('/adminRoute',function(){
        return 'adminDashboard';
    });
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/home', function () {
        // Check user role
        if (Auth::user()->role === 'admin') {
            return 'admin';
        } elseif (Auth::user()->role === 'user') {
            return 'user';
        } else {
            abort(403); 
        }
    });
});

require __DIR__.'/auth.php';
