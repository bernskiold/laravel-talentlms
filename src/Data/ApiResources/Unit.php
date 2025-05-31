<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class Unit
{

    public function __construct(
        public readonly int     $id,
        public readonly string  $type,
        public readonly string  $name,
        public readonly ?string $url = null,
        public readonly ?int    $aggregatedDelayTimeInMinutes = null,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            type: $response->type,
            name: $response->name,
            url: $response->url ?? null,
            aggregatedDelayTimeInMinutes: $response->aggregated_delay_time ?? null,
        );
    }

}
