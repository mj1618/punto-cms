<?php namespace App\AUI;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
class PuntoCMSServiceProvider extends BaseServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    // Can't enable this because there appears to be a bug in Laravel where a
    // non-deferred service provider can't use a deferred one because the boot
    // method is not called - see DependantServiceProviderTest.
    // protected $defer = true;
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['punto-cms'];
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $viewPath = __DIR__ . '/../views';
        $this->loadViewsFrom($viewPath, 'punto-cms');

        $publicPath = __DIR__.'/../public';

        $configFile = __DIR__ . '/../config/punto-cms.php';
        $this->mergeConfigFrom($configFile, 'punto-cms');

        $this->publishes([
            $configFile => config_path('punto-cms.php')
        ],'config');

        $this->publishes([
            $viewPath => base_path('resources/views/vendor/punto-cms')
        ],'views');

        $this->publishes([
            $publicPath => public_path('')
        ],'public');
    }
}