<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourse;

describe('UserCourse DTO', function () {
    it('can be instantiated with required parameters', function () {
        $userCourse = new UserCourse(id: 1, role: 'learner');

        expect($userCourse)->toBeInstanceOf(UserCourse::class);
        expect($userCourse->id)->toBe(1);
        expect($userCourse->role)->toBe('learner');
    });

    it('can be instantiated with all parameters', function () {
        $enrolledAt = \Carbon\Carbon::now();
        $completedAt = \Carbon\Carbon::now()->addDays(30);
        $expiredAt = \Carbon\Carbon::now()->addYear();

        $userCourse = new UserCourse(
            id: 1,
            role: 'learner',
            enrolledAt: $enrolledAt,
            completedAt: $completedAt,
            completionStatus: 'completed',
            completionPercentage: 100.0,
            expiredAt: $expiredAt,
            lastAccessedUnitUrl: 'https://example.com/unit/5',
        );

        expect($userCourse->id)->toBe(1);
        expect($userCourse->role)->toBe('learner');
        expect($userCourse->enrolledAt)->toBe($enrolledAt);
        expect($userCourse->completedAt)->toBe($completedAt);
        expect($userCourse->completionStatus)->toBe('completed');
        expect($userCourse->completionPercentage)->toBe(100.0);
        expect($userCourse->expiredAt)->toBe($expiredAt);
        expect($userCourse->lastAccessedUnitUrl)->toBe('https://example.com/unit/5');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'id' => 1,
            'role' => 'instructor',
            'enrolled_on_timestamp' => 1609459200,
            'completed_on_timestamp' => 1612137600,
            'completion_status' => 'completed',
            'completion_percentage' => 100,
            'expired_on_timestamp' => 1640995200,
            'last_accessed_unit_url' => 'https://example.com/course/unit/10',
        ];

        $userCourse = UserCourse::fromResponse($response);

        expect($userCourse->id)->toBe(1);
        expect($userCourse->role)->toBe('instructor');
        expect($userCourse->enrolledAt)->not->toBeNull();
        expect($userCourse->completedAt)->not->toBeNull();
        expect($userCourse->completionStatus)->toBe('completed');
        expect($userCourse->completionPercentage)->toBe(100.0);
        expect($userCourse->expiredAt)->not->toBeNull();
        expect($userCourse->lastAccessedUnitUrl)->toBe('https://example.com/course/unit/10');
    });

    it('handles missing optional fields', function () {
        $response = (object) [
            'id' => 1,
            'role' => 'learner',
        ];

        $userCourse = UserCourse::fromResponse($response);

        expect($userCourse->id)->toBe(1);
        expect($userCourse->role)->toBe('learner');
        expect($userCourse->enrolledAt)->toBeNull();
        expect($userCourse->completedAt)->toBeNull();
        expect($userCourse->completionStatus)->toBeNull();
        expect($userCourse->completionPercentage)->toBeNull();
        expect($userCourse->expiredAt)->toBeNull();
        expect($userCourse->lastAccessedUnitUrl)->toBeNull();
    });

    it('has readonly properties', function () {
        $userCourse = new UserCourse(id: 1, role: 'learner');

        expect(fn () => $userCourse->id = 2)->toThrow(Error::class);
    });
});
