<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\Group;
use Bernskiold\LaravelTalentLms\Data\ApiResources\GroupCourse;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroup;

describe('Group DTO', function () {
    it('can be instantiated with required parameters', function () {
        $group = new Group(id: 1, name: 'Test Group');

        expect($group)->toBeInstanceOf(Group::class);
        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Test Group');
    });

    it('can be instantiated with all parameters', function () {
        $group = new Group(
            id: 1,
            name: 'Premium Group',
            description: 'A premium group',
            key: 'premium-key',
            price: '99.99',
            ownerId: 5,
            belongsToBranch: 'Main Branch',
            maxRedemptions: 100,
            redemptionsSoFar: 50,
        );

        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Premium Group');
        expect($group->description)->toBe('A premium group');
        expect($group->key)->toBe('premium-key');
        expect($group->price)->toBe('99.99');
        expect($group->ownerId)->toBe(5);
        expect($group->belongsToBranch)->toBe('Main Branch');
        expect($group->maxRedemptions)->toBe(100);
        expect($group->redemptionsSoFar)->toBe(50);
    });

    it('can be created from API response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'description' => 'A test group description',
            'key' => 'test-key',
            'price' => '49.99',
            'owner_id' => 1,
            'belongs_to_branch' => 'Branch A',
            'max_redemptions' => 50,
            'redemptions_sofar' => 10,
        ];

        $group = Group::fromResponse($response);

        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Test Group');
        expect($group->description)->toBe('A test group description');
        expect($group->key)->toBe('test-key');
        expect($group->price)->toBe('49.99');
        expect($group->ownerId)->toBe(1);
        expect($group->belongsToBranch)->toBe('Branch A');
        expect($group->maxRedemptions)->toBe(50);
        expect($group->redemptionsSoFar)->toBe(10);
    });

    it('handles missing optional fields', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Minimal Group',
        ];

        $group = Group::fromResponse($response);

        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Minimal Group');
        expect($group->description)->toBeNull();
        expect($group->key)->toBeNull();
        expect($group->price)->toBeNull();
        expect($group->ownerId)->toBeNull();
    });

    it('handles empty belongs_to_branch', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'belongs_to_branch' => '',
        ];

        $group = Group::fromResponse($response);

        expect($group->belongsToBranch)->toBeNull();
    });

    it('handles zero max_redemptions', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'max_redemptions' => 0,
        ];

        $group = Group::fromResponse($response);

        expect($group->maxRedemptions)->toBe(0);
    });

    it('handles zero redemptions_sofar', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'redemptions_sofar' => 0,
        ];

        $group = Group::fromResponse($response);

        expect($group->redemptionsSoFar)->toBe(0);
    });

    it('parses users from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'users' => [
                (object) [
                    'id' => 1,
                    'name' => 'John Doe',
                ],
            ],
        ];

        $group = Group::fromResponse($response);

        expect($group->users)->toHaveCount(1);
        expect($group->users->first())->toBeInstanceOf(UserGroup::class);
    });

    it('parses courses from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Group',
            'courses' => [
                (object) [
                    'id' => 1,
                    'name' => 'Course 1',
                ],
            ],
        ];

        $group = Group::fromResponse($response);

        expect($group->courses)->toHaveCount(1);
        expect($group->courses->first())->toBeInstanceOf(GroupCourse::class);
    });

    it('has readonly properties', function () {
        $group = new Group(id: 1, name: 'Test');

        expect(fn () => $group->id = 2)->toThrow(Error::class);
    });
});
