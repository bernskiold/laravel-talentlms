<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class UserCertification
{

    public function __construct(
        public readonly int              $id,
        public readonly int              $courseId,
        public readonly string           $courseName,
        public readonly ?CarbonInterface $issuedAt = null,
        public readonly ?CarbonInterface $expiresAt = null,
        public readonly ?string          $downloadUrl = null,
        public readonly ?string          $publicUrl = null,
        public readonly Collection       $badges = new Collection()
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->unique_id,
            courseId: $response->course_id,
            courseName: $response->course_name,
            issuedAt: ApiParsing::parseTimestamp($response->issued_date_timestamp),
            expiresAt: ApiParsing::parseTimestamp($response->expiration_date_timestamp),
            downloadUrl: $response->download_url ?? null,
            publicUrl: $response->public_url ?? null,
            badges: collect($response->badges ?? [])
                ->map(fn($badge) => CertificationBadge::fromResponse($badge))
        );
    }

}
