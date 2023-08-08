<?php

use App\Http\Controllers\CourseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/courses',[CourseController::class,'index'])->name('courses.index');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/course/{id}',[CourseController::class,'show'])->name('courses.show'); Route::get('/course/{id}',[CourseController::class,'show'])->name('courses.show');
    Route::get('/courses/edit/{id}',[CourseController::class,'edit'])->name('courses.edit');
    Route::patch('/courses/{id}',[CourseController::class,'update'])->name('courses.update');
    Route::post('/toggleProgress',[CourseController::class,'toggleProgress'])->name('courses.toggle');
    Route::post('/courses',[CourseController::class,'store'])->name('courses.store');
});
