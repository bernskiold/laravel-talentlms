<?php

namespace Bernskiold\LaravelTalentLms\Facades;

use Bernskiold\LaravelTalentLms\Api\Resources;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Resources\BrandShare brandShares()
 *
 * @see \Bernskiold\LaravelTalentLms\Api\TalentLms
 */
class TalentLms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-talentlms';
    }
}
