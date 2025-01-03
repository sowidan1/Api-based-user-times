<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = User::where('isAdmin', 0)->paginate(10);

    return view('dashboard', compact('users'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/users/{user}/times', [ProfileController::class, 'updateTimes'])->name('users.update.times');
    Route::get('/users/times', [ProfileController::class, 'userTimes'])->name('users.times')->middleware('isTimesPostive');

});

require __DIR__.'/auth.php';
