<?php

use Bernskiold\LaravelTalentLms\Api\Resources\Users;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Facades\Http;

describe('Users Resource', function () {
    beforeEach(function () {
        $this->client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );
        $this->users = new Users($this->client);
    });

    describe('all()', function () {
        it('returns a ListResponse with users', function () {
            Http::fake([
                '*users*' => Http::response([
                    ['id' => 1, 'login' => 'user1', 'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
                    ['id' => 2, 'login' => 'user2', 'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com'],
                ], 200),
            ]);

            $result = $this->users->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(2);
            expect($result->get()->first())->toBeInstanceOf(User::class);
        });

        it('returns empty ListResponse when no users exist', function () {
            Http::fake([
                '*users*' => Http::response([], 200),
            ]);

            $result = $this->users->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(0);
        });
    });

    describe('get()', function () {
        it('returns a User when found', function () {
            Http::fake([
                '*users/id:1*' => Http::response([
                    'id' => 1,
                    'login' => 'johndoe',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                    'user_type' => 'Learner',
                    'status' => 'active',
                ], 200),
            ]);

            $result = $this->users->get(1);

            expect($result)->toBeInstanceOf(User::class);
            expect($result->id)->toBe(1);
            expect($result->username)->toBe('johndoe');
            expect($result->firstName)->toBe('John');
            expect($result->lastName)->toBe('Doe');
        });

        it('returns null when user not found', function () {
            Http::fake([
                '*users/id:999*' => Http::response(null, 200),
            ]);

            $result = $this->users->get(999);

            expect($result)->toBeNull();
        });
    });

    describe('create()', function () {
        it('creates a new user with minimal data', function () {
            Http::fake([
                '*usersignup*' => Http::response([
                    'id' => 1,
                    'login' => 'john@example.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ], 200),
            ]);

            $result = $this->users->create('John', 'Doe', 'john@example.com');

            expect($result)->toBeInstanceOf(User::class);
            expect($result->firstName)->toBe('John');
            expect($result->lastName)->toBe('Doe');
            expect($result->email)->toBe('john@example.com');
        });

        it('creates a new user with custom username and password', function () {
            Http::fake([
                '*usersignup*' => Http::response([
                    'id' => 1,
                    'login' => 'johndoe',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ], 200),
            ]);

            $result = $this->users->create('John', 'Doe', 'john@example.com', 'johndoe', 'password123');

            expect($result)->toBeInstanceOf(User::class);
            expect($result->username)->toBe('johndoe');

            Http::assertSent(function ($request) {
                $body = $request->body();

                return str_contains($body, 'login') && str_contains($body, 'password');
            });
        });
    });

    describe('lookupByEmail()', function () {
        it('returns a User when found by email', function () {
            Http::fake([
                '*users/email:john@example.com*' => Http::response([
                    [
                        'id' => 1,
                        'login' => 'johndoe',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john@example.com',
                    ],
                ], 200),
            ]);

            $result = $this->users->lookupByEmail('john@example.com');

            expect($result)->toBeInstanceOf(User::class);
            expect($result->email)->toBe('john@example.com');
        });

        it('returns null when user not found', function () {
            Http::fake([
                '*users/email:notfound@example.com*' => Http::response([], 200),
            ]);

            $result = $this->users->lookupByEmail('notfound@example.com');

            expect($result)->toBeNull();
        });

        it('returns null on request exception', function () {
            Http::fake([
                '*users/email:error@example.com*' => Http::response(null, 404),
            ]);

            $result = $this->users->lookupByEmail('error@example.com');

            expect($result)->toBeNull();
        });
    });

    describe('lookupByUsername()', function () {
        it('returns a User when found by username', function () {
            Http::fake([
                '*users/username:johndoe*' => Http::response([
                    [
                        'id' => 1,
                        'login' => 'johndoe',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john@example.com',
                    ],
                ], 200),
            ]);

            $result = $this->users->lookupByUsername('johndoe');

            expect($result)->toBeInstanceOf(User::class);
            expect($result->username)->toBe('johndoe');
        });

        it('returns null when user not found', function () {
            Http::fake([
                '*users/username:notfound*' => Http::response([], 200),
            ]);

            $result = $this->users->lookupByUsername('notfound');

            expect($result)->toBeNull();
        });
    });
});
