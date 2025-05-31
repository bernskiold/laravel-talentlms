<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class UserCourseUnenrollment
{

    public function __construct(
        public readonly int    $userId,
        public readonly int    $courseId,
        public readonly string $courseName,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            userId: $response->user_id,
            courseId: $response->course_id,
            courseName: $response->course_name,
        );
    }

}
