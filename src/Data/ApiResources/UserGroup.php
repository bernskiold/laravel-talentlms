<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class UserGroup
{

    public function __construct(
        public readonly int    $id,
        public readonly string $name,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name ?? '',
        );
    }

}
