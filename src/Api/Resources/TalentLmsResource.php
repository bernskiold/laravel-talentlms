<?php

namespace Bernskiold\LaravelTalentLms\Api\Resources;

use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;

class TalentLmsResource
{

    public function __construct(
        protected TalentLmsApiClient $client,
    )
    {
    }

}
