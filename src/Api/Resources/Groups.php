<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroup;
use Bernskiold\LaravelTalentLms\Data\ListResponse;

class Groups extends TalentLmsResource
{
    /**
     * Remove a user from a group.
     *
     * @param int|string $groupIdOrName
     * @param int|string $userIdOrEmail
     * @return bool
     */
    public function removeUser(int|string $groupIdOrName, int|string $userIdOrEmail): bool
    {
        $params = [];

        if (is_numeric($groupIdOrName)) {
            $params['group_id'] = $groupIdOrName;
        } else {
            $params['group_name'] = $groupIdOrName;
        }

        if (is_numeric($userIdOrEmail)) {
            $params['user_id'] = $userIdOrEmail;
        } else {
            $params['user_email'] = $userIdOrEmail;
        }

        $response = $this->client->post("/removeuserfromgroup", $params);

        return !empty($response);
    }

    /**
     * Add a user to a group.
     *
     * @param int|string $groupIdOrName
     * @param int|string $userIdOrEmail
     * @return bool
     */
    public function addUser(int|string $groupIdOrName, int|string $userIdOrEmail): bool
    {
        $params = [];

        if (is_numeric($groupIdOrName)) {
            $params['group_id'] = $groupIdOrName;
        } else {
            $params['group_name'] = $groupIdOrName;
        }

        if (is_numeric($userIdOrEmail)) {
            $params['user_id'] = $userIdOrEmail;
        } else {
            $params['user_email'] = $userIdOrEmail;
        }

        $response = $this->client->post("/addusertogroup", $params);

        return !empty($response);
    }

    /**
     * Get all groups.
     *
     * @return ListResponse
     */
    public function all(): ListResponse
    {
        $response = $this->client->get('/groups');

        return new ListResponse(
            data: array_map(static function ($row) {
                return UserGroup::fromResponse($row);
            }, $response ?? []),
        );
    }

    /**
     * Get a specific group.
     *
     * @param int $id
     * @return UserGroup|null
     */
    public function get(int $id): ?UserGroup
    {
        $response = $this->client->get("/groups/{$id}");

        if (empty($response)) {
            return null;
        }

        return UserGroup::fromResponse($response);
    }
}

