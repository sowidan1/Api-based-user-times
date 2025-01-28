<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = User::with('durations')->where('isAdmin', 0)->paginate(10);

    return view('dashboard', compact('users'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
// admin

    Route::post('/{user}/times', [PackageController::class, 'userTimesDashboard'])->name('users.update.times');

    Route::get('/{user}/one-time', [PackageController::class, 'oneTimeDashboard'])->name('one.time.dashboard');

    Route::post('/{user}/duration', [PackageController::class, 'durationDashboard'])->name('duration.dashboard');

// users

    Route::get('/times', [PackageController::class, 'userTimesApi'])->name('times')->middleware('isTimesPostive');

    Route::get('/one-time', [PackageController::class, 'oneTimeApi'])->name('one.time')->middleware('isRunBefore');

    Route::get('/duration', [PackageController::class, 'durationApi'])->name('duration')->middleware('isRunBefore');
});



require __DIR__ . '/auth.php';
