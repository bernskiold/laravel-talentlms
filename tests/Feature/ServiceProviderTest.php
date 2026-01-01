<?php

use Bernskiold\LaravelTalentLms\Api\TalentLms;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;

describe('LaravelTalentLmsServiceProvider', function () {
    it('registers the TalentLmsApiClient binding', function () {
        $client = app(TalentLmsApiClient::class);

        expect($client)->toBeInstanceOf(TalentLmsApiClient::class);
    });

    it('registers the laravel-talentlms alias', function () {
        $talentLms = app('laravel-talentlms');

        expect($talentLms)->toBeInstanceOf(TalentLms::class);
    });

    it('publishes the config file', function () {
        $this->artisan('vendor:publish', [
            '--provider' => 'Bernskiold\LaravelTalentLms\LaravelTalentLmsServiceProvider',
            '--tag' => 'config',
        ]);

        expect(config('talentlms'))->toBeArray();
        expect(config('talentlms.api'))->toHaveKeys(['api_key', 'domain', 'version']);
    });

    it('loads the config file', function () {
        expect(config('talentlms.api.api_key'))->toBe('test-api-key');
        expect(config('talentlms.api.domain'))->toBe('https://test.talentlms.com');
        expect(config('talentlms.api.version'))->toBe('1');
    });

    it('registers the rate limiter', function () {
        $limiter = app(\Illuminate\Cache\RateLimiting\Limit::class);

        // Rate limiter is registered, we can check if the RateLimiter facade has a 'talentlms' limiter
        expect(\Illuminate\Support\Facades\RateLimiter::limiter('talentlms'))->not->toBeNull();
    });
});
