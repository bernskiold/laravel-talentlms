<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Data\ApiResources\Group;
use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroupEnrollment;
use Bernskiold\LaravelTalentLms\Data\ListResponse;

class Groups extends TalentLmsResource
{
    public function all(): ListResponse
    {
        $response = $this->client->get('/groups');

        return new ListResponse(
            data: array_map(static function ($row) {
                return Group::fromResponse($row);
            }, $response ?? []),
        );
    }

    public function get(int $id): ?Group
    {
        $response = $this->client->get("/groups/{$id}");

        if (empty($response)) {
            return null;
        }

        return Group::fromResponse($response);
    }

    public function create(string $name, ?string $description = null, ?string $price = null, ?string $key = null, ?int $maxRedemptions = null, ?int $creatorId = null): Group
    {
        $args = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'key' => $key,
            'max_redemptions' => $maxRedemptions,
            'creator_id' => $creatorId,
        ];

        // Remove null values from the arguments
        $args = collect($args)->filter()->all();

        $response = $this->client->post('/groups', $args);

        return Group::fromResponse($response);
    }

    public function addUser(Group|string $groupKey, User|int $userId)
    {
        $groupKey = $groupKey instanceof Group ? $groupKey->key : $groupKey;
        $userId = $userId instanceof User ? $userId->id : $userId;

        $response = $this->client->get("/addusertogroup", [
            'user_id' => $userId,
            'group_key' => $groupKey,
        ]);

        return UserGroupEnrollment::fromResponse($response);
    }

    public function removeUser(Group|string $groupKey, User|int $userId)
    {
        $groupKey = $groupKey instanceof Group ? $groupKey->key : $groupKey;
        $userId = $userId instanceof User ? $userId->id : $userId;

        $response = $this->client->get("/removeusertogroup", [
            'user_id' => $userId,
            'group_key' => $groupKey,
        ]);

        return UserGroupEnrollment::fromResponse($response);
    }
}
