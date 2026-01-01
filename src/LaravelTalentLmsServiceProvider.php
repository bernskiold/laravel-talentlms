<?php

namespace Bernskiold\LaravelTalentLms;

use Bernskiold\LaravelTalentLms\Api\TalentLms;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

use function config;

class LaravelTalentLmsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel TalentLMS', fn () => ['Version' => '1.0.0']);

        RateLimiter::for('talentlms', function () {
            return Limit::perSecond(200, 5);
        });

        $this->publishes([
            __DIR__.'/../config/talentlms.php' => config_path('talentlms.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/talentlms.php', 'talentlms'
        );

        $this->app->singleton(TalentLmsApiClient::class, function () {
            return TalentLmsApiClient::fromConfig(config('talentlms.api'));
        });

        $this->app->singleton(TalentLms::class, function ($app) {
            return new TalentLms($app->make(TalentLmsApiClient::class));
        });

        $this->app->alias(TalentLms::class, 'laravel-talentlms');
    }
}
