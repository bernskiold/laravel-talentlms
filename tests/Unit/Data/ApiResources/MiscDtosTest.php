<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\CertificationBadge;
use Bernskiold\LaravelTalentLms\Data\ApiResources\GroupCourse;
use Bernskiold\LaravelTalentLms\Data\ApiResources\PrerequisiteCourse;
use Bernskiold\LaravelTalentLms\Data\ApiResources\PrerequisiteRuleSet;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserBranch;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCertification;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroup;

describe('UserBranch DTO', function () {
    it('can be instantiated', function () {
        $branch = new UserBranch(id: 1, name: 'Main Branch');

        expect($branch)->toBeInstanceOf(UserBranch::class);
        expect($branch->id)->toBe(1);
        expect($branch->name)->toBe('Main Branch');
    });

    it('can be created from API response', function () {
        $response = (object) ['id' => 1, 'name' => 'Test Branch'];
        $branch = UserBranch::fromResponse($response);

        expect($branch->id)->toBe(1);
        expect($branch->name)->toBe('Test Branch');
    });

    it('handles missing name', function () {
        $response = (object) ['id' => 1];
        $branch = UserBranch::fromResponse($response);

        expect($branch->name)->toBe('');
    });
});

describe('UserGroup DTO', function () {
    it('can be instantiated', function () {
        $group = new UserGroup(id: 1, name: 'Test Group');

        expect($group)->toBeInstanceOf(UserGroup::class);
        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Test Group');
    });

    it('can be created from API response', function () {
        $response = (object) ['id' => 1, 'name' => 'Premium Members'];
        $group = UserGroup::fromResponse($response);

        expect($group->id)->toBe(1);
        expect($group->name)->toBe('Premium Members');
    });

    it('handles missing name', function () {
        $response = (object) ['id' => 1];
        $group = UserGroup::fromResponse($response);

        expect($group->name)->toBe('');
    });
});

describe('GroupCourse DTO', function () {
    it('can be instantiated', function () {
        $course = new GroupCourse(id: 1, name: 'Test Course');

        expect($course)->toBeInstanceOf(GroupCourse::class);
        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Test Course');
    });

    it('can be created from API response', function () {
        $response = (object) ['id' => 1, 'name' => 'Introduction Course'];
        $course = GroupCourse::fromResponse($response);

        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Introduction Course');
    });
});

describe('PrerequisiteCourse DTO', function () {
    it('can be instantiated', function () {
        $course = new PrerequisiteCourse(id: 1, name: 'Basic Course');

        expect($course)->toBeInstanceOf(PrerequisiteCourse::class);
        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Basic Course');
    });

    it('can be created from API response', function () {
        $response = (object) ['course_id' => 1, 'course_name' => 'Prerequisite Course'];
        $course = PrerequisiteCourse::fromResponse($response);

        expect($course->id)->toBe(1);
        expect($course->name)->toBe('Prerequisite Course');
    });
});

describe('PrerequisiteRuleSet DTO', function () {
    it('can be instantiated', function () {
        $ruleSet = new PrerequisiteRuleSet(id: 1, name: 'Test Course', ruleSet: 'all');

        expect($ruleSet)->toBeInstanceOf(PrerequisiteRuleSet::class);
        expect($ruleSet->id)->toBe(1);
        expect($ruleSet->name)->toBe('Test Course');
        expect($ruleSet->ruleSet)->toBe('all');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'course_id' => 1,
            'course_name' => 'Advanced Course',
            'rule_set' => 'any',
        ];
        $ruleSet = PrerequisiteRuleSet::fromResponse($response);

        expect($ruleSet->id)->toBe(1);
        expect($ruleSet->name)->toBe('Advanced Course');
        expect($ruleSet->ruleSet)->toBe('any');
    });
});

describe('UserCertification DTO', function () {
    it('can be instantiated', function () {
        $cert = new UserCertification(
            id: 'cert-123',
            courseId: 1,
            courseName: 'Test Course',
        );

        expect($cert)->toBeInstanceOf(UserCertification::class);
        expect($cert->id)->toBe('cert-123');
        expect($cert->courseId)->toBe(1);
        expect($cert->courseName)->toBe('Test Course');
    });

    it('can be created from API response', function () {
        $response = (object) [
            'unique_id' => 'cert-456',
            'course_id' => 2,
            'course_name' => 'Certified Course',
            'issued_date_timestamp' => 1609459200,
            'expiration_date_timestamp' => 1640995200,
            'download_url' => 'https://example.com/cert.pdf',
            'public_url' => 'https://example.com/verify/cert-456',
            'badges' => [],
        ];
        $cert = UserCertification::fromResponse($response);

        expect($cert->id)->toBe('cert-456');
        expect($cert->courseId)->toBe(2);
        expect($cert->courseName)->toBe('Certified Course');
        expect($cert->issuedAt)->not->toBeNull();
        expect($cert->expiresAt)->not->toBeNull();
        expect($cert->downloadUrl)->toBe('https://example.com/cert.pdf');
        expect($cert->publicUrl)->toBe('https://example.com/verify/cert-456');
        expect($cert->badges)->toHaveCount(0);
    });
});
