<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\User;
use App\Models\Event;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [

    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        /*Gate::define('update-event', function (User $user, Event $event) {
            return $user->id === $event->user_id;
        });
        Gate::define('delete-attendee',function (User $user,Event $event ,Attendee $attendee) {
            return $user->id === $attendee->user_id ||  $user->id === $event-> user_id   ;
        });*/
    }
}
