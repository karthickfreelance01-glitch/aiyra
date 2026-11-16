<?php

namespace App\Providers;

use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share site settings with every view as $site
        View::composer('*', function ($view) {
            static $site = null;
            if ($site === null) {
                try {
                    $site = Setting::allCached();
                } catch (\Throwable $e) {
                    $site = [];
                }
            }
            $view->with('site', $site);
        });

        // Unread enquiry count for the admin sidebar
        View::composer('admin.layouts.app', function ($view) {
            try {
                $view->with('unreadCount', Enquiry::unread()->count());
            } catch (\Throwable $e) {
                $view->with('unreadCount', 0);
            }
        });
    }
}
