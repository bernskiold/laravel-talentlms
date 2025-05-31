<?php

namespace Bernskiold\LaravelTalentLms\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class ApiParsing
{

    public static function parseBoolean(mixed $value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        // We keep this as non-strict comparison on purposes.
        return !($value == '0');
    }

    public static function parseTimestamp(mixed $timestamp): ?CarbonInterface
    {
        if(empty($timestamp)) {
            return null;
        }

        return Carbon::createFromTimestamp($timestamp);
    }

    public static function parseDateTime(mixed $dateTime): ?CarbonInterface
    {
        if (empty($dateTime)) {
            return null;
        }

        return Carbon::parse($format, $dateTime);
    }

}
