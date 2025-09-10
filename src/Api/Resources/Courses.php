<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Data\ApiResources\Course;
use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserCourseEnrollment;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Str;
use function array_map;
use function is_numeric;

class Courses extends TalentLmsResource
{

    public function all(): ListResponse
    {
        $response = $this->client->get('/courses');

        return new ListResponse(
            data: array_map(static function ($row) {
                return Course::fromResponse($row);
            }, $response ?? []),
        );
    }

    public function get(int $id): ?Course
    {
        $response = $this->client->get("/courses/id:{$id}");

        if (empty($response)) {
            return null;
        }

        return Course::fromResponse($response);
    }

    public function enrollUser(
        int|string $courseIdOrName,
        int|string $userIdOrEmail,
        bool $instructor = false
    ): ?UserCourseEnrollment {
        $params = [
            'role' => $instructor ? 'instructor' : 'learner',
        ];

        if (is_numeric($courseIdOrName)) {
            $params['course_id'] = $courseIdOrName;
        } else {
            $params['course_name'] = $courseIdOrName;
        }

        if (is_numeric($userIdOrEmail)) {
            $params['user_id'] = $userIdOrEmail;
        } else {
            $params['user_email'] = $userIdOrEmail;
        }

        $response = $this->client->get("/addusertocourse", $params);

        if (!$response) {
            return null;
        }

        return UserCourseEnrollment::fromResponse($response);
    }

    public function unenrollUser(int|string $courseId, int|string $userId): ?UserCourseEnrollment
    {
        $params = [
            'course_id' => $courseId,
            'user_id' => $userId,
        ];

        $response = $this->client->get("/removeuserfromcourse", $params);

        if (!$response) {
            return null;
        }

        return UserCourseEnrollment::fromResponse($response);
    }

    public function courseLoginUrl(int $userId, int $courseId): ?string
    {
        $response = $this->client->get('/gotocourse', [
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);

        if (empty($response)) {
            return null;
        }

        return $response->goto_url ?? null;
    }
}
