<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class Course
{

    public function __construct(
        public readonly int              $id,
        public readonly string           $name,
        public readonly ?string          $code,
        public readonly ?int             $categoryId,
        public readonly ?string          $description,
        public readonly ?string          $price,
        public readonly ?string          $sattus,
        public readonly ?CarbonInterface $createdAt,
        public readonly ?CarbonInterface $updatedAt,
        public readonly ?int             $createdById,
        public readonly bool             $hideFromCatalog,
        public readonly bool             $hasTimeLimit,
        public readonly ?CarbonInterface $startsAt,
        public readonly ?CarbonInterface $expiresAt,
        public readonly ?int             $level,
        public readonly bool             $isShared,
        public readonly ?string          $sharedUrl,
        public readonly ?string          $avatarUrl,
        public readonly ?string          $bigAvatarUrl,
        public readonly ?string          $certification,
        public readonly ?string          $certificationDuration,
        public readonly Collection       $users = new Collection(),
        public readonly Collection       $units = new Collection(),
        public readonly Collection       $rules = new Collection(),
        public readonly Collection       $prerequisites = new Collection(),
        public readonly Collection       $prerequisiteRuleSets = new Collection()
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            name: $response->name,
            code: $response->code ?? null,
            categoryId: $response->category_id ?? null,
            description: $response->description ?? null,
            price: $response->price ?? null,
            sattus: $response->status ?? null,
            createdAt: ApiParsing::parseDateTime($response->creation_date ?? null),
            updatedAt: ApiParsing::parseDateTime($response->last_update_on ?? null),
            createdById: $response->creator_id ?? null,
            hideFromCatalog: ApiParsing::parseBoolean($response->hide_from_catalog ?? false),
            hasTimeLimit: ApiParsing::parseBoolean($response->time_limit ?? false),
            startsAt: ApiParsing::parseDateTime($response->start_datetime ?? null),
            expiresAt: ApiParsing::parseDateTime($response->expiration_datetime ?? null),
            level: $response->level ?? null,
            isShared: ApiParsing::parseBoolean($response->shared ?? false),
            sharedUrl: $response->shared_url ?? null,
            avatarUrl: $response->avatar ?? null,
            bigAvatarUrl: $response->big_avatar ?? null,
            certification: $response->certification ?? null,
            certificationDuration: $response->certification_duration ?? null,
            users: collect($response->users ?? [])->map(fn($user) => UserCourse::fromResponse($user)),
            units: collect($response->units ?? [])->map(fn($unit) => Unit::fromResponse($unit)),
            rules: collect($response->rules ?? []),
            prerequisites: collect($response->prerequisites ?? [])->map(fn($prerequisite) => PrerequisiteCourse::fromResponse($prerequisite)),
            prerequisiteRuleSets: collect($response->prerequisite_rule_sets ?? [])->map(fn($ruleSet) => PrerequisiteRuleSet::fromResponse($ruleSet))
        );
    }

}
