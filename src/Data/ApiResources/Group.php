<?php

namespace Bernskiold\LaravelTalentLms\Data\ApiResources;

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class Group
{

    public function __construct(
        public readonly int        $id,
        public readonly string     $name,
        public readonly ?string    $description = null,
        public readonly ?string    $key = null,
        public readonly ?string    $price = null,
        public readonly ?int       $ownerId = null,
        public readonly ?string    $belongsToBranch = null,
        public readonly ?int       $maxRedemptions = null,
        public readonly ?int       $redemptionsSoFar = null,
        public readonly Collection $users = new Collection(),
        public readonly Collection $courses = new Collection(),
    )
    {
    }

    public static function fromResponse(object $response): self
    {
        $maxRedemptions = $response->max_redemptions ?? null;
        $redemptionsSoFar = $response->redemptions_sofar ?? null;

        return new self(
            id: $response->id,
            name: $response->name,
            description: $response->description ?? null,
            key: $response->key ?? null,
            price: $response->price ?? null,
            ownerId: $response->owner_id ?? null,
            belongsToBranch: empty($response->belongs_to_branch ?? null) ? null : $response->belongs_to_branch,
            maxRedemptions: $maxRedemptions === null || ($maxRedemptions === '' && $maxRedemptions !== 0) ? null : (int) $maxRedemptions,
            redemptionsSoFar: $redemptionsSoFar === null || ($redemptionsSoFar === '' && $redemptionsSoFar !== 0) ? null : (int) $redemptionsSoFar,
            users: collect($response->users ?? [])
                ->map(fn ($user) => UserGroup::fromResponse($user)),
            courses: collect($response->courses ?? [])
                ->map(fn ($course) => GroupCourse::fromResponse($course))
        );
    }
}
