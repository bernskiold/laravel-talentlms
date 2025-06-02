<?php

namespace Bernskiold\LaravelTalentLms\Api;


class TalentLms
{
    public function __construct(
        protected TalentLmsApiClient $apiClient,
    )
    {
    }

    public function courses(): Resources\Courses
    {
        return new Resources\Courses($this->apiClient);
    }

    public function users(): Resources\Users
    {
        return new Resources\Users($this->apiClient);
    }

    public function groups(): Resources\Groups
    {
        return new Resources\Groups($this->apiClient);
    }
}

