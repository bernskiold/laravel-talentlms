<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class PrerequisiteRuleSet
{

    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $ruleSet,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->course_id,
            name: $response->course_name,
            ruleSet: $response->rule_set,
        );
    }
}
