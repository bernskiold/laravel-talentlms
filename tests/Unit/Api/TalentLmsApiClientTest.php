<?php

use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

describe('TalentLmsApiClient', function () {
    it('can be instantiated with required parameters', function () {
        $client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );

        expect($client)->toBeInstanceOf(TalentLmsApiClient::class);
    });

    it('can be created from config array', function () {
        $client = TalentLmsApiClient::fromConfig([
            'api_key' => 'test-api-key',
            'domain' => 'https://test.talentlms.com',
            'version' => '1',
        ]);

        expect($client)->toBeInstanceOf(TalentLmsApiClient::class);
    });

    it('uses default version when not provided in config', function () {
        $client = TalentLmsApiClient::fromConfig([
            'api_key' => 'test-api-key',
            'domain' => 'https://test.talentlms.com',
        ]);

        expect($client)->toBeInstanceOf(TalentLmsApiClient::class);
    });

    it('makes GET requests to the correct endpoint', function () {
        Http::fake([
            '*' => Http::response(['id' => 1, 'name' => 'Test Course'], 200),
        ]);

        $client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );

        $result = $client->get('/courses');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.talentlms.com/api/v1/courses');
        });
    });

    it('makes POST requests to the correct endpoint', function () {
        Http::fake([
            '*' => Http::response(['id' => 1, 'first_name' => 'John'], 200),
        ]);

        $client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );

        $result = $client->post('/usersignup', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                str_contains($request->url(), 'https://test.talentlms.com/api/v1/usersignup');
        });
    });

    it('builds query parameters correctly', function () {
        Http::fake([
            '*' => Http::response(['id' => 1], 200),
        ]);

        $client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );

        $client->get('/users', ['id' => 123]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/users/id:123');
        });
    });

    it('sends basic auth with API key', function () {
        Http::fake([
            '*' => Http::response(['id' => 1], 200),
        ]);

        $client = new TalentLmsApiClient(
            apiKey: 'my-secret-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );

        $client->get('/courses');

        Http::assertSent(function ($request) {
            $authHeader = $request->header('Authorization')[0] ?? '';
            return str_starts_with($authHeader, 'Basic ');
        });
    });
});
