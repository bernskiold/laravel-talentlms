<?php

namespace Bernskiold\LaravelTalentLms\Api;

use Bernskiold\LaravelTalentLms\Api\Resources\BrandShare;

/**
 * Euromonitor API
 *
 * This class is the main entry point for the Euromonitor API.
 * It provides access to all the different resources available.
 *
 * You can either call this class directly via the container or
 * use the Euromonitor facade provided by the package.
 */
class TalentLms
{
    public function __construct(
        protected TalentLmsApiClient $apiClient,
    ) {}

    public function brandShares(): BrandShare
    {
        return new BrandShare($this->apiClient);
    }

}
