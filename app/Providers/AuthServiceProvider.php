<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Course' => 'App\Policies\coursePolicy',
        'App\Models\Episode' => 'App\Policies\EpisodePolicy',
        'App\Models\Discussion' => 'App\Policies\DiscussionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update-course',function($course){
            return $course->user_id==auth()->user()->id;
        });
        
        Gate::define('manage-reports', function($user) {
            return $user->hasRole('admin');
        });
    }
}
