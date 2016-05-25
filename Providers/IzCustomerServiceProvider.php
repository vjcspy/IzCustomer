<?php namespace Modules\Izcustomer\Providers;

use Illuminate\Support\ServiceProvider;

class IzCustomerServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot() {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        //
        $this->registerDependencyLibrary();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig() {
        $this->publishes(
            [
                __DIR__ . '/../Config/config.php' => config_path('izcustomer.php'),
            ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'izcustomer'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews() {
        $viewPath = base_path('resources/views/modules/izcustomer');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes(
            [
                $sourcePath => $viewPath
            ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(
                    function ($path) {
                        return $path . '/modules/izcustomer';
                    },
                    \Config::get('view.paths')),
                [$sourcePath]),
            'izcustomer');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations() {
        $langPath = base_path('resources/lang/modules/izcustomer');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'izcustomer');
        }
        else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'izcustomer');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [];
    }

    public function registerDependencyLibrary() {
        /*Sentinel*/
        $this->app->register(\Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class);
        /*Facebook SDK*/
        $this->app->register(\SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider::class);
    }
}
