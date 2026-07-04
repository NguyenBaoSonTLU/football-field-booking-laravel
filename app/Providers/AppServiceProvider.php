<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Đăng ký service container tại đây khi dự án mở rộng.
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
