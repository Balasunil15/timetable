<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\userController;
use App\Http\Controllers\FacultyController;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    // Redirect root to the login page
    return redirect()->route('login');
});

// Removed name from POST login route to avoid conflict.
Route::post('/login', [userController::class, 'login']);

Route::get('/hoddashboard', function () {
    if (!session()->has('fid')) {
        return redirect()->route('login');
    }
    $response = response()->view('hoddashboard');
    return $response
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('hoddashboard');

Route::get('/facultydashboard', function () {
    if (!session()->has('fid')) {
        return redirect()->route('login');
    }
    $response = response()->view('facultydashboard');
    return $response
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('facultydashboard');

Route::get('/logout', [userController::class, 'logout'])->name('logout');

// Added routes for subjects and advisors
Route::get('/subjects', [userController::class, 'subjects'])->name('subjects');
Route::post('/subjects/store', [userController::class, 'storeCourse'])->name('subjects.store');
Route::post('/subjects/update', [userController::class, 'updateCourse'])->name('subjects.update');
Route::post('/subjects/delete', [userController::class, 'deleteCourse'])->name('subjects.delete');
Route::get('/advisors', [userController::class, 'advisors'])->name('advisors');
Route::post('/advisor/store', [userController::class, 'storeAdvisor'])->name('advisor.store');
// Added route for updating advisor
Route::post('/advisor/update', [userController::class, 'updateAdvisor'])->name('advisor.update');

Route::get('/advisor-subjects', [userController::class, 'advisorSubjects'])->name('advisorsubjects');
Route::get('/studentslist', [userController::class, 'studentslist'])->name('studentslist');