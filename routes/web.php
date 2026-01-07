<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FileUploadController;
use App\Models\Category;
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
Route::get('/course/{id}',[CourseController::class,'show'])->name('courses.show');
Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
Route::get('/categories/{id}',[CategoryController::class,'show'])->name('categories.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user()->load('course');

        $stats = [
            'my_courses' => $user->course->count(),
            'is_instructor' => $user->hasRole('instructor'),
            'enrolled_courses' => 0,
        ];

        $myCourses = $user->course()->latest()->take(6)->get(['id', 'title', 'created_at']);

        return Inertia::render('Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'myCourses' => $myCourses,
            'categories' => Category::all(),
        ]);
    })->name('dashboard');
    
    // Mes formations (accessible à tous les utilisateurs authentifiés)
    Route::get('/my-courses',[CourseController::class,'myCourses'])->name('courses.my');
    
    // Instructor routes - require instructor or admin role
    Route::middleware(['role:instructor|admin'])->group(function () {
        Route::get('/courses/create',[CourseController::class,'create'])->name('courses.create');
        Route::get('/courses/edit/{id}',[CourseController::class,'edit'])->name('courses.edit');
        Route::get('/courses/{id}/episodes/create',[CourseController::class,'createEpisode'])->name('episodes.create');
        Route::patch('/courses/{id}',[CourseController::class,'update'])->name('courses.update');
        Route::post('/courses',[CourseController::class,'store'])->name('courses.store');
        Route::post('/episodes',[CourseController::class,'storeEpisode'])->name('episodes.store');
        // Quick create (from dashboard)
        Route::post('/courses/quick',[CourseController::class,'storeQuick'])->name('courses.quick');
    });
    
    // Progress tracking - accessible à tous les utilisateurs authentifiés
    Route::post('/toggleProgress',[CourseController::class,'toggleProgress'])->name('courses.toggle');
});

// Admin routes - require admin role
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:admin',
])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/instructors', [AdminController::class, 'instructors'])->name('admin.instructors');
    Route::post('/instructors/{user}/approve', [AdminController::class, 'approveInstructor'])->name('admin.instructors.approve');
    Route::post('/instructors/{user}/revoke', [AdminController::class, 'revokeInstructor'])->name('admin.instructors.revoke');
    Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
    
    // Role management routes
    Route::post('/roles', [AdminController::class, 'storeRole'])->name('admin.roles.store');
    Route::patch('/roles/{role}', [AdminController::class, 'updateRole'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])->name('admin.roles.destroy');
    
    // User role management routes
    Route::post('/users/assign-role', [AdminController::class, 'assignRole'])->name('admin.users.assign-role');
    Route::post('/users/remove-role', [AdminController::class, 'removeRole'])->name('admin.users.remove-role');
});

// File upload routes (authenticated)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
    Route::post('/upload/profile-photo', [FileUploadController::class, 'uploadProfilePhoto'])->name('upload.profile-photo');
    Route::delete('/upload', [FileUploadController::class, 'delete'])->name('upload.delete');
});
