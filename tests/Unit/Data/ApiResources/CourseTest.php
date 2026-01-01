<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\Course;
use Bernskiold\LaravelTalentLms\Data\ApiResources\PrerequisiteCourse;
use Bernskiold\LaravelTalentLms\Data\ApiResources\PrerequisiteRuleSet;
use Bernskiold\LaravelTalentLms\Data\ApiResources\Unit;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourse;

describe('Course DTO', function () {
    it('can be instantiated with required parameters', function () {
        $course = new Course(
            id: 1,
            name: 'Test Course',
            code: null,
            categoryId: null,
            description: null,
            price: null,
            sattus: null,
            createdAt: null,
            updatedAt: null,
            createdById: null,
            hideFromCatalog: false,
            hasTimeLimit: false,
            startsAt: null,
            expiresAt: null,
            level: null,
            isShared: false,
            sharedUrl: null,
            avatarUrl: null,
            bigAvatarUrl: null,
            certification: null,
            certificationDuration: null,
        );

        expect($course)->toBeInstanceOf(Course::class);
        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Test Course');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Introduction to Testing',
            'code' => 'ITT101',
            'category_id' => 5,
            'description' => 'A course about testing',
            'price' => '99.99',
            'status' => 'active',
            'creation_date' => '2021-01-01 10:00:00',
            'last_update_on' => '2021-06-15 14:30:00',
            'creator_id' => 1,
            'hide_from_catalog' => '0',
            'time_limit' => '1',
            'start_datetime' => '2021-02-01 00:00:00',
            'expiration_datetime' => '2021-12-31 23:59:59',
            'level' => 2,
            'shared' => '1',
            'shared_url' => 'https://example.com/shared/course',
            'avatar' => 'https://example.com/avatar.png',
            'big_avatar' => 'https://example.com/big_avatar.png',
            'certification' => 'Test Certification',
            'certification_duration' => '1 year',
        ];

        $course = Course::fromResponse($response);

        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Introduction to Testing');
        expect($course->code)->toBe('ITT101');
        expect($course->categoryId)->toBe(5);
        expect($course->description)->toBe('A course about testing');
        expect($course->price)->toBe('99.99');
        expect($course->sattus)->toBe('active');
        expect($course->createdById)->toBe(1);
        expect($course->hideFromCatalog)->toBeFalse();
        expect($course->hasTimeLimit)->toBeTrue();
        expect($course->level)->toBe(2);
        expect($course->isShared)->toBeTrue();
        expect($course->sharedUrl)->toBe('https://example.com/shared/course');
        expect($course->avatarUrl)->toBe('https://example.com/avatar.png');
        expect($course->bigAvatarUrl)->toBe('https://example.com/big_avatar.png');
        expect($course->certification)->toBe('Test Certification');
        expect($course->certificationDuration)->toBe('1 year');
        expect($course->createdAt)->not->toBeNull();
        expect($course->updatedAt)->not->toBeNull();
        expect($course->startsAt)->not->toBeNull();
        expect($course->expiresAt)->not->toBeNull();
    });

    it('handles missing optional fields', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Minimal Course',
        ];

        $course = Course::fromResponse($response);

        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Minimal Course');
        expect($course->code)->toBeNull();
        expect($course->categoryId)->toBeNull();
        expect($course->description)->toBeNull();
        expect($course->createdAt)->toBeNull();
        expect($course->hideFromCatalog)->toBeFalse();
    });

    it('parses users from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Course',
            'users' => [
                (object) [
                    'id' => 1,
                    'role' => 'learner',
                    'enrolled_on_timestamp' => 1609459200,
                    'completion_status' => 'completed',
                ],
            ],
        ];

        $course = Course::fromResponse($response);

        expect($course->users)->toHaveCount(1);
        expect($course->users->first())->toBeInstanceOf(UserCourse::class);
    });

    it('parses units from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Course',
            'units' => [
                (object) [
                    'id' => 1,
                    'type' => 'video',
                    'name' => 'Introduction Video',
                    'url' => 'https://example.com/video',
                ],
            ],
        ];

        $course = Course::fromResponse($response);

        expect($course->units)->toHaveCount(1);
        expect($course->units->first())->toBeInstanceOf(Unit::class);
    });

    it('parses prerequisites from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Advanced Course',
            'prerequisites' => [
                (object) [
                    'course_id' => 2,
                    'course_name' => 'Basic Course',
                ],
            ],
        ];

        $course = Course::fromResponse($response);

        expect($course->prerequisites)->toHaveCount(1);
        expect($course->prerequisites->first())->toBeInstanceOf(PrerequisiteCourse::class);
    });

    it('parses prerequisite rule sets from response', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Advanced Course',
            'prerequisite_rule_sets' => [
                (object) [
                    'course_id' => 2,
                    'course_name' => 'Prerequisite Course',
                    'rule_set' => 'all',
                ],
            ],
        ];

        $course = Course::fromResponse($response);

        expect($course->prerequisiteRuleSets)->toHaveCount(1);
        expect($course->prerequisiteRuleSets->first())->toBeInstanceOf(PrerequisiteRuleSet::class);
    });

    it('parses rules as collection', function () {
        $response = (object) [
            'id' => 1,
            'name' => 'Test Course',
            'rules' => [
                'rule1' => 'value1',
                'rule2' => 'value2',
            ],
        ];

        $course = Course::fromResponse($response);

        expect($course->rules)->toHaveCount(2);
    });

    it('has readonly properties', function () {
        $course = new Course(
            id: 1,
            name: 'Test',
            code: null,
            categoryId: null,
            description: null,
            price: null,
            sattus: null,
            createdAt: null,
            updatedAt: null,
            createdById: null,
            hideFromCatalog: false,
            hasTimeLimit: false,
            startsAt: null,
            expiresAt: null,
            level: null,
            isShared: false,
            sharedUrl: null,
            avatarUrl: null,
            bigAvatarUrl: null,
            certification: null,
            certificationDuration: null,
        );

        expect(fn () => $course->id = 2)->toThrow(Error::class);
    });
});
