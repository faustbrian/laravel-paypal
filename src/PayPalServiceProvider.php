<?php

declare(strict_types=1);

/*
 * This file is part of Laravel PayPal.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\PayPal;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

class PayPalServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-paypal.php' => config_path('laravel-paypal.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-paypal.php', 'laravel-paypal');

        $this->registerFactory();

        $this->registerManager();

        $this->registerBindings();
    }

    /**
     * Register the factory class.
     */
    protected function registerFactory()
    {
        $this->app->singleton('paypal.factory', function () {
            return new PayPalFactory();
        });

        $this->app->alias('paypal.factory', PayPalFactory::class);
    }

    /**
     * Register the manager class.
     */
    protected function registerManager()
    {
        $this->app->singleton('paypal', function (Container $app) {
            $config = $app['config'];
            $factory = $app['paypal.factory'];

            return new PayPalManager($config, $factory);
        });

        $this->app->alias('paypal', PayPalManager::class);
    }

    /**
     * Register the bindings.
     */
    protected function registerBindings()
    {
        $this->app->bind('paypal.connection', function (Container $app) {
            $manager = $app['paypal'];

            return $manager->connection();
        });

        $this->app->alias('paypal.connection', PayPal::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'paypal',
            'paypal.factory',
            'paypal.connection',
        ];
    }
}
