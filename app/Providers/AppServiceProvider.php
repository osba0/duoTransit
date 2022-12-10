<?php

namespace App\Providers;

use Blade, App;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

     /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bladeDirectives();
         $this->app->bind('path.public', function() {
            return base_path().'/../public_html';
        });
    }
    private function bladeDirectives()
    {
        Blade::directive('userCan', function ($expression) {
            return "<?php if (\\Auth::check()
             && (\\Auth::guard()->user()->hasRole({$expression}) || \\Auth::guard()->user()->hasRole('" . App\Models\UserRole::ROLE_ROOT . "'))) : ?>";
        });
        Blade::directive('endUserCan', function () {
            return "<?php endif; ?>";
        });
    }
}
