<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\userController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;

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

Route::get('/advisorsubjects', [userController::class, 'advisorsubjects'])->name('advisorsubjects');
Route::get('/studentslist', [userController::class, 'studentslist'])->name('studentslist');
Route::get('/timetable', [userController::class, 'timetable'])->name('timetable');

// Added route for assigning subject
Route::post('/subject/assign', [userController::class, 'assignSubject'])->name('subject.assign');
Route::post('/subject/remove', [userController::class, 'removeSubject'])->name('subject.remove');

// Updated route to use userController for fetching students
Route::get('/students/fetch', [userController::class, 'fetchStudents'])->name('students.fetch');

// Added route to fetch subjects dynamically for the students tab
Route::get('/subjects/fetch', [userController::class, 'fetchSubjects'])->name('subjects.fetch');