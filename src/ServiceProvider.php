<?php

namespace LaravelFeiShu;

use FeiShu\OpenPlatform\Application as OpenPlatform;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('feishu.php')], 'laravel-feishu');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('feishu');
        }

        $this->mergeConfigFrom($source, 'feishu');
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->setupConfig();

        $apps = [
            'open_platform' => OpenPlatform::class,
        ];

        foreach ($apps as $name => $class) {
            if (empty(config('feishu.'.$name))) {
                continue;
            }

            if ($config = config('feishu.route.'.$name)) {
                $this->getRouter()->group($config['attributes'], function ($router) use ($config) {
                    $router->post($config['uri'], $config['action']);
                });
            }

            if (!empty(config('feishu.'.$name.'.app_id')) || !empty(config('feishu.'.$name.'.corp_id'))) {
                $accounts = [
                    'default' => config('feishu.'.$name),
                ];
                config(['feishu.'.$name.'.default' => $accounts['default']]);
            } else {
                $accounts = config('feishu.'.$name);
            }

            foreach ($accounts as $account => $config) {
                $this->app->bind("feishu.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('feishu.defaults', []), $config));
                    if (config('feishu.defaults.use_laravel_cache')) {
                        $app['cache'] = $laravelApp['cache.store'];
                    }
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
            }
            $this->app->alias("feishu.{$name}.default", 'feishu.'.$name);
            $this->app->alias("feishu.{$name}.default", 'easyfeishu.'.$name);

            $this->app->alias('feishu.'.$name, $class);
            $this->app->alias('easyfeishu.'.$name, $class);
        }
    }

    protected function getRouter()
    {
        if ($this->app instanceof LumenApplication && !class_exists('Laravel\Lumen\Routing\Router')) {
            return $this->app;
        }

        return $this->app->router;
    }
}
