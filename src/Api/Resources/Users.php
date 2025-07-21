<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Str;

class Users extends TalentLmsResource
{

    public function all(): ListResponse
    {
        $response = $this->client->get('/users');

        return new ListResponse(
            data: array_map(static function ($row) {
                return User::fromResponse($row);
            }, $response ?? []),
        );
    }

    public function get(int $id): ?User
    {
        $response = $this->client->get("/users/{$id}");

        if (empty($response)) {
            return null;
        }

        return User::fromResponse($response);
    }

    public function create(string $firstName, string $lastName, string $email, ?string $username = null, ?string $password = null): User
    {
        $response = $this->client->post('/users', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'username' => $username ?? $email,
            'password' => $password ?? Str::random(32),
        ]);

        return User::fromResponse($response);
    }

    public function lookupByEmail(string $email): ?User
    {
        try {
            $response = $this->client->get('/users', [
                'email' => $email,
            ]);

            if (empty($response)) {
                return null;
            }

            return User::fromResponse($response[0]);
        } catch (RequestException $e) {
            return null;
        }
    }

    public function lookupByUsername(string $username): ?User
    {
        try {
            $response = $this->client->get('/users', [
                'username' => $username,
            ]);

            if (empty($response)) {
                return null;
            }

            return User::fromResponse($response[0]);
        } catch (RequestException $e) {
            return null;
        }
    }
}
