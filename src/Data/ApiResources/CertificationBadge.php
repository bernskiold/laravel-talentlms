<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;

class CertificationBadge
{

    public function __construct(
        public readonly string           $name,
        public readonly string           $type,
        public readonly ?string          $imageUrl = null,
        public readonly ?string          $criteria = null,
        public readonly ?CarbonInterface $issuedAt = null,
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            name: $response->name ?? '',
            type: $response->type ?? '',
            imageUrl: $response->image_url ?? null,
            criteria: $response->criteria ?? null,
            issuedAt: ApiParsing::parseTimestamp($response->issued_on_timestamp ?? null),
        );
    }

}
