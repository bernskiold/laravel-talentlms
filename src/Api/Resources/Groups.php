<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Data\ApiResources\UserGroupConnection;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Str;

class Groups extends TalentLmsResource
{

    public function addUser(int $userId, string $groupKey ): ?UserGroupConnection
    {
        $params = [
            'user_id' => $userId,
            'group_key' => $groupKey,
        ];

        $response = $this->client->post("/addUserToGroup", $params);

        if (!$response) {
            return null;
        }

        return UserGroupConnection::fromResponse($response);
    }

    public function removeUser(int $userId, string $groupKey): ?UserGroupConnection
    {
        $params = [
            'user_id' => $userId,
            'group_key' => $groupKey,
        ];

        $response = $this->client->post("/removeUserFromGroup", $params);

        if (!$response) {
            return null;
        }

        return UserGroupConnection::fromResponse($response);
    }

}
