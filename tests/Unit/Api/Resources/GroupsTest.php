<?php

use Bernskiold\LaravelTalentLms\Api\Resources\Groups;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Bernskiold\LaravelTalentLms\Data\ApiResources\Group;
use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroupEnrollment;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Facades\Http;

describe('Groups Resource', function () {
    beforeEach(function () {
        $this->client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );
        $this->groups = new Groups($this->client);
    });

    describe('all()', function () {
        it('returns a ListResponse with groups', function () {
            Http::fake([
                '*groups*' => Http::response([
                    ['id' => 1, 'name' => 'Group 1', 'key' => 'group-1'],
                    ['id' => 2, 'name' => 'Group 2', 'key' => 'group-2'],
                ], 200),
            ]);

            $result = $this->groups->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(2);
            expect($result->get()->first())->toBeInstanceOf(Group::class);
        });

        it('returns empty ListResponse when no groups exist', function () {
            Http::fake([
                '*groups*' => Http::response([], 200),
            ]);

            $result = $this->groups->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(0);
        });
    });

    describe('get()', function () {
        it('returns a Group when found', function () {
            Http::fake([
                '*groups/id:1*' => Http::response([
                    'id' => 1,
                    'name' => 'Test Group',
                    'description' => 'A test group',
                    'key' => 'test-group',
                    'price' => '0',
                ], 200),
            ]);

            $result = $this->groups->get(1);

            expect($result)->toBeInstanceOf(Group::class);
            expect($result->id)->toBe(1);
            expect($result->name)->toBe('Test Group');
            expect($result->key)->toBe('test-group');
        });

        it('returns null when group not found', function () {
            Http::fake([
                '*groups/id:999*' => Http::response(null, 200),
            ]);

            $result = $this->groups->get(999);

            expect($result)->toBeNull();
        });
    });

    describe('create()', function () {
        it('creates a new group with minimal data', function () {
            Http::fake([
                '*groups*' => Http::response([
                    'id' => 1,
                    'name' => 'New Group',
                    'key' => 'new-group',
                ], 200),
            ]);

            $result = $this->groups->create('New Group');

            expect($result)->toBeInstanceOf(Group::class);
            expect($result->name)->toBe('New Group');
        });

        it('creates a new group with all optional parameters', function () {
            Http::fake([
                '*groups*' => Http::response([
                    'id' => 1,
                    'name' => 'Premium Group',
                    'description' => 'A premium group',
                    'key' => 'premium-key',
                    'price' => '99.99',
                    'max_redemptions' => 100,
                    'owner_id' => 1,
                ], 200),
            ]);

            $result = $this->groups->create(
                name: 'Premium Group',
                description: 'A premium group',
                price: '99.99',
                key: 'premium-key',
                maxRedemptions: 100,
                creatorId: 1
            );

            expect($result)->toBeInstanceOf(Group::class);
            expect($result->name)->toBe('Premium Group');
            expect($result->price)->toBe('99.99');
        });
    });

    describe('addUser()', function () {
        it('adds user to group by group key and user ID', function () {
            Http::fake([
                '*addusertogroup*' => Http::response([
                    'user_id' => 1,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $result = $this->groups->addUser('group-key', 1);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
            expect($result->userId)->toBe(1);
            expect($result->groupId)->toBe(2);
            expect($result->groupName)->toBe('Test Group');
        });

        it('adds user to group using Group object', function () {
            Http::fake([
                '*addusertogroup*' => Http::response([
                    'user_id' => 1,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $group = new Group(id: 2, name: 'Test Group', key: 'test-key');
            $result = $this->groups->addUser($group, 1);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
        });

        it('adds user to group using User object', function () {
            Http::fake([
                '*addusertogroup*' => Http::response([
                    'user_id' => 5,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $user = new User(id: 5, username: 'testuser');
            $result = $this->groups->addUser('group-key', $user);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
            expect($result->userId)->toBe(5);
        });
    });

    describe('removeUser()', function () {
        it('removes user from group by IDs', function () {
            Http::fake([
                '*removeuserfromgroup*' => Http::response([
                    'user_id' => 1,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $result = $this->groups->removeUser(2, 1);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
            expect($result->userId)->toBe(1);
            expect($result->groupId)->toBe(2);
        });

        it('removes user from group using Group object', function () {
            Http::fake([
                '*removeuserfromgroup*' => Http::response([
                    'user_id' => 1,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $group = new Group(id: 2, name: 'Test Group');
            $result = $this->groups->removeUser($group, 1);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
        });

        it('removes user from group using User object', function () {
            Http::fake([
                '*removeuserfromgroup*' => Http::response([
                    'user_id' => 5,
                    'group_id' => 2,
                    'group_name' => 'Test Group',
                ], 200),
            ]);

            $user = new User(id: 5, username: 'testuser');
            $result = $this->groups->removeUser(2, $user);

            expect($result)->toBeInstanceOf(UserGroupEnrollment::class);
            expect($result->userId)->toBe(5);
        });
    });
});
