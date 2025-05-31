<?php

namespace Bernskiold\LaravelTalentLms\Exceptions;

use Exception;

class TalentLmsApiException extends Exception
{
    public static function noAccessToken(Exception $e): self
    {
        return new static('The access token could not be retrieved from Euromonitor.', previous: $e);
    }

    public static function missingApiKey(): self
    {
        return new static('The TalentLMS API key is missing. Please set the TALENTLMS_API_KEY environment variable.');
    }

    public static function missingDomain(): self
    {
        return new static('The TalentLMS domain is missing. Please set the TALENTLMS_DOMAIN environment variable.');
    }
}
