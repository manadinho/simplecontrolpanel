<?php

namespace Wikichua\SimpleControlPanel;

use Illuminate\Support\ServiceProvider;

class SimpleControlPanelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wikichua');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'wikichua');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->gatePermissions();
        $this->validatorExtensions();
        $this->configSettings();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lap.php', 'lap');

        // Register the service the package provides.
        $this->app->singleton('simplecontrolpanel', function ($app) {
            return new SimpleControlPanel;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['simplecontrolpanel'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([__DIR__.'/../config/simplecontrolpanel.php' => config_path('lap.php')], 'install');
        $this->publishes([__DIR__ . '/../public' => public_path('lap')], 'install'); // public assets
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/lap')], 'install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/BackendController.stub' => app_path('Http/Controllers/Admin/BackendController.php')], 'install'); // backend controller

        $this->publishes([__DIR__ . '/../public' => public_path('lap')], 'config');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lap');

        $this->publishes([__DIR__ . '/../resources/stubs/crud/default' => resource_path('stubs/crud/default')], 'crud_stubs');
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/wikichua'),
        ], 'simplecontrolpanel.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/wikichua'),
        ], 'simplecontrolpanel.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/wikichua'),
        ], 'simplecontrolpanel.views');*/

        // Registering package commands.
        $this->commands([
                Commands\CrudConfig::class,
                Commands\CrudGenerate::class,
        ]);
        
        // alias middleware
        $this->app['router']->prependMiddlewareToGroup('web', 'Wikichua\SimpleControlPanel\Middleware\RestrictDemo');
        $this->app['router']->aliasMiddleware('auth_admin', 'Wikichua\SimpleControlPanel\Middleware\AuthAdmin');
        $this->app['router']->aliasMiddleware('guest_admin', 'Wikichua\SimpleControlPanel\Middleware\GuestAdmin');
        $this->app['router']->aliasMiddleware('intend_url', 'Wikichua\SimpleControlPanel\Middleware\IntendUrl');
        $this->app['router']->aliasMiddleware('not_admin_role', 'Wikichua\SimpleControlPanel\Middleware\NotAdminRole');
        $this->app['router']->aliasMiddleware('not_system_doc', 'Wikichua\SimpleControlPanel\Middleware\NotSystemDoc');
    }

    public function gatePermissions()
    {
        Gate::before(function ($user, $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        });
    }

    public function validatorExtensions()
    {
        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, auth()->user()->password);
        }, 'The current password is invalid.');
    }

    public function configSettings()
    {
        if (Schema::hasTable('settings')) {
            foreach (app(config('lap.models.setting'))->all() as $setting) {
                Config::set('settings.' . $setting->key, $setting->value);
            }
        }
    }
}
