<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserBranch;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCertification;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourse;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroup;

describe('User DTO', function () {
    it('can be instantiated with required parameters', function () {
        $user = new User(id: 1);

        expect($user)->toBeInstanceOf(User::class);
        expect($user->id)->toBe(1);
    });

    it('can be instantiated with all parameters', function () {
        $user = new User(
            id: 1,
            username: 'johndoe',
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com',
            restrictEmail: true,
            userType: 'Learner',
            timezone: 'UTC',
            language: 'en',
            status: 'active',
        );

        expect($user->id)->toBe(1);
        expect($user->username)->toBe('johndoe');
        expect($user->firstName)->toBe('John');
        expect($user->lastName)->toBe('Doe');
        expect($user->email)->toBe('john@example.com');
        expect($user->restrictEmail)->toBeTrue();
        expect($user->userType)->toBe('Learner');
        expect($user->timezone)->toBe('UTC');
        expect($user->language)->toBe('en');
        expect($user->status)->toBe('active');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'id' => 1,
            'login' => 'johndoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'restrict_email' => '1',
            'user_type' => 'Learner',
            'timezone' => 'America/New_York',
            'language' => 'en',
            'status' => 'active',
            'level' => 5,
            'points' => 100,
            'created_on_timestamp' => 1609459200,
            'last_updated_timestamp' => 1609545600,
            'avatar' => 'https://example.com/avatar.png',
            'bio' => 'A test user',
            'login_key' => 'abc123',
        ];

        $user = User::fromResponse($response);

        expect($user->id)->toBe(1);
        expect($user->username)->toBe('johndoe');
        expect($user->firstName)->toBe('John');
        expect($user->lastName)->toBe('Doe');
        expect($user->email)->toBe('john@example.com');
        expect($user->restrictEmail)->toBeTrue();
        expect($user->userType)->toBe('Learner');
        expect($user->timezone)->toBe('America/New_York');
        expect($user->language)->toBe('en');
        expect($user->status)->toBe('active');
        expect($user->level)->toBe(5);
        expect($user->points)->toBe(100);
        expect($user->avatarUrl)->toBe('https://example.com/avatar.png');
        expect($user->biography)->toBe('A test user');
        expect($user->loginKey)->toBe('abc123');
        expect($user->createdAt)->not->toBeNull();
        expect($user->updatedAt)->not->toBeNull();
    });

    it('handles missing optional fields', function () {
        $response = (object) [
            'id' => 1,
        ];

        $user = User::fromResponse($response);

        expect($user->id)->toBe(1);
        expect($user->username)->toBeNull();
        expect($user->firstName)->toBeNull();
        expect($user->lastName)->toBeNull();
        expect($user->email)->toBeNull();
        expect($user->restrictEmail)->toBeFalse();
        expect($user->createdAt)->toBeNull();
    });

    it('parses courses from response', function () {
        $response = (object) [
            'id' => 1,
            'courses' => [
                (object) [
                    'id' => 1,
                    'role' => 'learner',
                    'enrolled_on_timestamp' => 1609459200,
                ],
            ],
        ];

        $user = User::fromResponse($response);

        expect($user->courses)->toHaveCount(1);
        expect($user->courses->first())->toBeInstanceOf(UserCourse::class);
    });

    it('parses branches from response', function () {
        $response = (object) [
            'id' => 1,
            'branches' => [
                (object) [
                    'id' => 1,
                    'name' => 'Main Branch',
                ],
            ],
        ];

        $user = User::fromResponse($response);

        expect($user->branches)->toHaveCount(1);
        expect($user->branches->first())->toBeInstanceOf(UserBranch::class);
    });

    it('parses groups from response', function () {
        $response = (object) [
            'id' => 1,
            'groups' => [
                (object) [
                    'id' => 1,
                    'name' => 'Test Group',
                ],
            ],
        ];

        $user = User::fromResponse($response);

        expect($user->groups)->toHaveCount(1);
        expect($user->groups->first())->toBeInstanceOf(UserGroup::class);
    });

    it('parses certifications from response', function () {
        $response = (object) [
            'id' => 1,
            'certifications' => [
                (object) [
                    'course_id' => 1,
                    'course_name' => 'Test Course',
                    'unique_id' => 'cert-123',
                    'issued_date' => '2021-01-01',
                ],
            ],
        ];

        $user = User::fromResponse($response);

        expect($user->certifications)->toHaveCount(1);
        expect($user->certifications->first())->toBeInstanceOf(UserCertification::class);
    });

    it('has readonly properties', function () {
        $user = new User(id: 1, username: 'test');

        expect(fn () => $user->id = 2)->toThrow(Error::class);
    });
});
