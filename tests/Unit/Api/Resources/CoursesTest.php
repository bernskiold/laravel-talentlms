<?php

use Bernskiold\LaravelTalentLms\Api\Resources\Courses;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;
use Bernskiold\LaravelTalentLms\Data\ApiResources\Course;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourseEnrollment;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Facades\Http;

describe('Courses Resource', function () {
    beforeEach(function () {
        $this->client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );
        $this->courses = new Courses($this->client);
    });

    describe('all()', function () {
        it('returns a ListResponse with courses', function () {
            Http::fake([
                '*courses*' => Http::response([
                    ['id' => 1, 'name' => 'Course 1', 'code' => 'C1'],
                    ['id' => 2, 'name' => 'Course 2', 'code' => 'C2'],
                ], 200),
            ]);

            $result = $this->courses->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(2);
            expect($result->get()->first())->toBeInstanceOf(Course::class);
        });

        it('returns empty ListResponse when no courses exist', function () {
            Http::fake([
                '*courses*' => Http::response([], 200),
            ]);

            $result = $this->courses->all();

            expect($result)->toBeInstanceOf(ListResponse::class);
            expect($result->get())->toHaveCount(0);
        });
    });

    describe('get()', function () {
        it('returns a Course when found', function () {
            Http::fake([
                '*courses/id:1*' => Http::response([
                    'id' => 1,
                    'name' => 'Test Course',
                    'code' => 'TC1',
                    'description' => 'A test course',
                ], 200),
            ]);

            $result = $this->courses->get(1);

            expect($result)->toBeInstanceOf(Course::class);
            expect($result->id)->toBe(1);
            expect($result->name)->toBe('Test Course');
        });

        it('returns null when course not found', function () {
            Http::fake([
                '*courses/id:999*' => Http::response(null, 200),
            ]);

            $result = $this->courses->get(999);

            expect($result)->toBeNull();
        });
    });

    describe('enrollUser()', function () {
        it('enrolls user by IDs', function () {
            Http::fake([
                '*addusertocourse*' => Http::response([
                    'user_id' => 1,
                    'course_id' => 2,
                    'role' => 'learner',
                ], 200),
            ]);

            $result = $this->courses->enrollUser(2, 1);

            expect($result)->toBeInstanceOf(UserCourseEnrollment::class);
            expect($result->userId)->toBe(1);
            expect($result->courseId)->toBe(2);
            expect($result->role)->toBe('learner');
        });

        it('enrolls user by email and course name', function () {
            Http::fake([
                '*addusertocourse*' => Http::response([
                    'user_id' => 1,
                    'course_id' => 2,
                    'role' => 'learner',
                ], 200),
            ]);

            $result = $this->courses->enrollUser('Course Name', 'user@example.com');

            expect($result)->toBeInstanceOf(UserCourseEnrollment::class);
        });

        it('enrolls user as instructor', function () {
            Http::fake([
                '*addusertocourse*' => Http::response([
                    'user_id' => 1,
                    'course_id' => 2,
                    'role' => 'instructor',
                ], 200),
            ]);

            $result = $this->courses->enrollUser(2, 1, true);

            expect($result)->toBeInstanceOf(UserCourseEnrollment::class);
            expect($result->role)->toBe('instructor');
        });

        it('returns null on empty response', function () {
            Http::fake([
                '*addusertocourse*' => Http::response(null, 200),
            ]);

            $result = $this->courses->enrollUser(2, 1);

            expect($result)->toBeNull();
        });
    });

    describe('unenrollUser()', function () {
        it('unenrolls user from course', function () {
            Http::fake([
                '*removeuserfromcourse*' => Http::response([
                    'user_id' => 1,
                    'course_id' => 2,
                    'role' => 'learner',
                ], 200),
            ]);

            $result = $this->courses->unenrollUser(2, 1);

            expect($result)->toBeInstanceOf(UserCourseEnrollment::class);
            expect($result->userId)->toBe(1);
            expect($result->courseId)->toBe(2);
        });

        it('returns null on empty response', function () {
            Http::fake([
                '*removeuserfromcourse*' => Http::response(null, 200),
            ]);

            $result = $this->courses->unenrollUser(2, 1);

            expect($result)->toBeNull();
        });
    });

    describe('courseLoginUrl()', function () {
        it('returns the course login URL', function () {
            Http::fake([
                '*gotocourse*' => Http::response([
                    'goto_url' => 'https://test.talentlms.com/course/login/123',
                ], 200),
            ]);

            $result = $this->courses->courseLoginUrl(1, 2);

            expect($result)->toBe('https://test.talentlms.com/course/login/123');
        });

        it('returns null when response is empty', function () {
            Http::fake([
                '*gotocourse*' => Http::response(null, 200),
            ]);

            $result = $this->courses->courseLoginUrl(1, 2);

            expect($result)->toBeNull();
        });

        it('returns null when goto_url is missing', function () {
            Http::fake([
                '*gotocourse*' => Http::response(['other_field' => 'value'], 200),
            ]);

            $result = $this->courses->courseLoginUrl(1, 2);

            expect($result)->toBeNull();
        });
    });
});
