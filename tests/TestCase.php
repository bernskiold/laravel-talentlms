<?php

namespace Bernskiold\LaravelTalentLms\Tests;

use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Bernskiold\LaravelTalentLms\LaravelTalentLmsServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTalentLmsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('talentlms.api.api_key', 'test-api-key');
        config()->set('talentlms.api.domain', 'https://test.talentlms.com');
        config()->set('talentlms.api.version', '1');
    }

    protected function createMockApiClient(): TalentLmsApiClient
    {
        return new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );
    }

    protected function fakeHttpResponse(string $endpoint, array|object $response, int $status = 200): void
    {
        Http::fake([
            "*{$endpoint}*" => Http::response($response, $status),
        ]);
    }

    protected function fakeHttpResponses(array $responses): void
    {
        $fakes = [];
        foreach ($responses as $endpoint => $response) {
            $fakes["*{$endpoint}*"] = Http::response($response, 200);
        }
        Http::fake($fakes);
    }
}
