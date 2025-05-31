<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class UserCourse
{

    public function __construct(
        public readonly int              $id,
        public readonly string           $role,
        public readonly ?CarbonInterface $enrolledAt = null,
        public readonly ?CarbonInterface $completedAt = null,
        public readonly ?string          $completionStatus = null,
        public readonly ?float           $completionPercentage = null,
        public readonly ?CarbonInterface $expiredAt = null,
        public readonly ?string          $lastAccessedUnitUrl = null,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            role: $response->role,
            enrolledAt: ApiParsing::parseTimestamp($response->enrolled_on_timestamp ?? null),
            completedAt: ApiParsing::parseTimestamp($response->completed_on_timestamp ?? null),
            completionStatus: $response->completion_status ?? null,
            completionPercentage: $response->completion_percentage ?? null,
            expiredAt: ApiParsing::parseTimestamp($response->expired_on_timestamp ?? null),
            lastAccessedUnitUrl: $response->last_accessed_unit_url ?? null,
        );
    }

}
