<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class UserGroupEnrollment
{

    public function __construct(
        public readonly int    $userId,
        public readonly int    $groupId,
        public readonly string $groupName,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            userId: $response->user_id,
            groupId: $response->group_id,
            groupName: $response->group_name,
        );
    }

}
