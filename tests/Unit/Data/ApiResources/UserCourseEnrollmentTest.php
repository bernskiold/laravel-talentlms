<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourseEnrollment;

describe('UserCourseEnrollment DTO', function () {
    it('can be instantiated with required parameters', function () {
        $enrollment = new UserCourseEnrollment(
            userId: 1,
            courseId: 2,
            role: 'learner',
        );

        expect($enrollment)->toBeInstanceOf(UserCourseEnrollment::class);
        expect($enrollment->userId)->toBe(1);
        expect($enrollment->courseId)->toBe(2);
        expect($enrollment->role)->toBe('learner');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'user_id' => 5,
            'course_id' => 10,
            'role' => 'instructor',
        ];

        $enrollment = UserCourseEnrollment::fromResponse($response);

        expect($enrollment->userId)->toBe(5);
        expect($enrollment->courseId)->toBe(10);
        expect($enrollment->role)->toBe('instructor');
    });

    it('handles missing role', function () {
        $response = (object) [
            'user_id' => 5,
            'course_id' => 10,
        ];

        $enrollment = UserCourseEnrollment::fromResponse($response);

        expect($enrollment->userId)->toBe(5);
        expect($enrollment->courseId)->toBe(10);
        expect($enrollment->role)->toBeNull();
    });

    it('has readonly properties', function () {
        $enrollment = new UserCourseEnrollment(
            userId: 1,
            courseId: 2,
            role: 'learner',
        );

        expect(fn () => $enrollment->userId = 5)->toThrow(Error::class);
    });
});
