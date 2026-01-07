<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DiscussionController;
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
        $user = auth()->user()->load('course', 'episodes', 'roles');

        // Calculate enrolled courses (courses user has followed episodes from)
        $enrolledCoursesIds = $user->episodes()
            ->select('episodes.course_id')
            ->distinct()
            ->pluck('course_id')
            ->toArray();

        $stats = [
            'my_courses' => $user->course->count(),
            'is_instructor' => $user->hasRole('instructor'),
            'is_admin' => $user->hasRole('admin'),
            'enrolled_courses' => count($enrolledCoursesIds),
            'role_names' => $user->role_names,
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
        Route::delete('/episodes/{id}',[CourseController::class,'deleteEpisode'])->name('episodes.delete');
        Route::patch('/episodes/{id}/status',[CourseController::class,'updateEpisodeStatus'])->name('episodes.status');
        Route::post('/courses/{id}/toggle-publish',[CourseController::class,'togglePublish'])->name('courses.toggle-publish');
        // Quick create (from dashboard)
        Route::post('/courses/quick',[CourseController::class,'storeQuick'])->name('courses.quick');
    });
    
    // Reporting routes - accessible à tous les utilisateurs authentifiés
    Route::post('/episodes/{id}/report', [ReportController::class, 'reportEpisode'])->name('episodes.report');
    
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
    
    // Reports management routes
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/all', [ReportController::class, 'allReports'])->name('admin.reports.all');
    Route::patch('/reports/{id}', [ReportController::class, 'updateReport'])->name('admin.reports.update');
});

// Discussion routes (authenticated)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/episodes/{episode}/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::patch('/discussions/{discussion}', [DiscussionController::class, 'update'])->name('discussions.update');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');
    Route::patch('/discussions/{discussion}/toggle-solved', [DiscussionController::class, 'toggleSolved'])->name('discussions.toggleSolved');
    Route::post('/discussions/{discussion}/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');
    Route::patch('/discussion-replies/{reply}/helpful', [DiscussionController::class, 'toggleHelpful'])->name('discussions.toggleHelpful');
    Route::patch('/discussion-replies/{reply}/mark-answer', [DiscussionController::class, 'markAsAnswer'])->name('discussions.markAsAnswer');
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
