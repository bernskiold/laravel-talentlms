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
        return new self(
            id: $response->id,
            name: $response->name,
            description: $response->description ?? null,
            key: $response->key ?? null,
            price: $response->price ?? null,
            ownerId: $response->owner_id ?? null,
            belongsToBranch: empty($response->belongs_to_branch) ? null : $response->belongs_to_branch,
            maxRedemptions: empty($response->max_redemptions) && $response->max_redemptions != 0 ? null : $response->max_redemptions,
            redemptionsSoFar: empty($response->redemptions_sofar) && $response->redemptions_sofar != 0 ? null : $response->redemptions_sofar,
            users: collect($response->users ?? [])
                ->map(fn($user) => UserGroup::fromResponse($user)),
            courses: collect($response->courses ?? [])
                ->map(fn($course) => GroupCourse::fromResponse($course))
        );
    }
}
