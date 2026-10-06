<?php

namespace App\Providers;

use App\Models\ContactQuery;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Paginator::defaultView('admin.include.pagination');

        Blade::directive('adminDate', function ($expression) {
            return "<?php echo optional($expression)->format('d/m/Y'); ?>";
        });

        View::composer('*', function ($view): void {
            try {
                $setting = Schema::hasTable('settings') ? Setting::query()->first() : null;
            } catch (\Throwable) {
                $setting = null;
            }

            try {
                $contactQueryNewCount = Schema::hasTable('contact_queries')
                    ? ContactQuery::query()->where('status', 'new')->count()
                    : 0;
            } catch (\Throwable) {
                $contactQueryNewCount = 0;
            }

            $view->with([
                'restaurantSetting' => $setting,
                'contactQueryNewCount' => $contactQueryNewCount,
            ]);
        });
    }
}
