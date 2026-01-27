<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Jacobtims\FilamentLogger\Resources\ActivityResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use App\Policies\ActivityPolicy;
use App\Policies\ToolPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);

        Gate::define('assign', [ToolPolicy::class, 'assign']);
        Gate::define('returnTool', [ToolPolicy::class, 'returnTool']);
        Gate::define('changeStatus', [ToolPolicy::class, 'changeStatus']);
        Gate::define('viewAssignmentHistory', [ToolPolicy::class, 'viewAssignmentHistory']);
        Gate::define('viewStatusHistory', [ToolPolicy::class, 'viewStatusHistory']);
    }
}
