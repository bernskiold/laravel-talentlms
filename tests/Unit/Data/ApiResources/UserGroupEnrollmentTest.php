<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroupEnrollment;

describe('UserGroupEnrollment DTO', function () {
    it('can be instantiated with required parameters', function () {
        $enrollment = new UserGroupEnrollment(
            userId: 1,
            groupId: 2,
            groupName: 'Test Group',
        );

        expect($enrollment)->toBeInstanceOf(UserGroupEnrollment::class);
        expect($enrollment->userId)->toBe(1);
        expect($enrollment->groupId)->toBe(2);
        expect($enrollment->groupName)->toBe('Test Group');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'user_id' => 5,
            'group_id' => 10,
            'group_name' => 'Premium Members',
        ];

        $enrollment = UserGroupEnrollment::fromResponse($response);

        expect($enrollment->userId)->toBe(5);
        expect($enrollment->groupId)->toBe(10);
        expect($enrollment->groupName)->toBe('Premium Members');
    });

    it('has readonly properties', function () {
        $enrollment = new UserGroupEnrollment(
            userId: 1,
            groupId: 2,
            groupName: 'Test Group',
        );

        expect(fn () => $enrollment->userId = 5)->toThrow(Error::class);
    });
});
