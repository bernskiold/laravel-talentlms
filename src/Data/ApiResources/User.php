<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class User
{

    public function __construct(
        public readonly int              $id,
        public readonly ?string          $username = null,
        public readonly ?string          $firstName = null,
        public readonly ?string          $lastName = null,
        public readonly ?string          $email = null,
        public readonly bool             $restrictEmail = false,
        public readonly ?string          $userType = null,
        public readonly ?string          $timezone = null,
        public readonly ?string          $language = null,
        public readonly ?string          $status = null,
        public readonly ?CarbonInterface $deactivationDate = null,
        public readonly ?int             $level = null,
        public readonly ?int             $points = null,
        public readonly ?CarbonInterface $createdAt = null,
        public readonly ?CarbonInterface $updatedAt = null,
        public readonly ?string          $avatarUrl = null,
        public readonly ?string          $biography = null,
        public readonly ?string          $loginKey = null,
        public readonly Collection       $courses = new Collection(),
        public readonly Collection       $branches = new Collection(),
        public readonly Collection       $groups = new Collection(),
        public readonly Collection       $certifications = new Collection(),
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        return new self(
            id: $response->id,
            username: $response->login ?? null,
            firstName: $response->first_name ?? null,
            lastName: $response->last_name ?? null,
            email: $response->email ?? null,
            restrictEmail: ApiParsing::parseBoolean($response->restrict_email ?? null),
            userType: $response->user_type ?? null,
            timezone: $response->timezone ?? null,
            language: $response->language ?? null,
            status: $response->status ?? null,
            deactivationDate: ApiParsing::parseTimestamp($response->deactivation_date_timestamp ?? null),
            level: $response->level ?? null,
            points: $response->points ?? null,
            createdAt: ApiParsing::parseTimestamp($response->created_on_timestamp ?? null),
            updatedAt: ApiParsing::parseTimestamp($response->last_updated_timestamp ?? null),
            avatarUrl: $response->avatar ?? null,
            biography: $response->bio ?? null,
            loginKey: $response->login_key ?? null,
            courses: collect($response->courses ?? [])
                ->map(fn($course) => UserCourse::fromResponse($course)),
            branches: collect($response->branches ?? [])
                ->map(fn($branch) => UserBranch::fromResponse($branch)),
            groups: collect($response->groups ?? [])
                ->map(fn($group) => UserGroup::fromResponse($group)),
            certifications: collect($response->certifications ?? [])
                ->map(fn($certification) => UserCertification::fromResponse($certification))
        );
    }
}
