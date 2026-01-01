<?php

use Bernskiold\LaravelTalentLms\Support\ApiParsing;
use Carbon\Carbon;
use Carbon\CarbonInterface;

describe('ApiParsing', function () {
    describe('parseBoolean()', function () {
        it('returns false for null', function () {
            expect(ApiParsing::parseBoolean(null))->toBeFalse();
        });

        it('returns true for boolean true', function () {
            expect(ApiParsing::parseBoolean(true))->toBeTrue();
        });

        it('returns false for boolean false', function () {
            expect(ApiParsing::parseBoolean(false))->toBeFalse();
        });

        it('returns true for string "1"', function () {
            expect(ApiParsing::parseBoolean('1'))->toBeTrue();
        });

        it('returns false for string "0"', function () {
            expect(ApiParsing::parseBoolean('0'))->toBeFalse();
        });

        it('returns true for integer 1', function () {
            expect(ApiParsing::parseBoolean(1))->toBeTrue();
        });

        it('returns false for integer 0', function () {
            expect(ApiParsing::parseBoolean(0))->toBeFalse();
        });

        it('returns true for non-zero string', function () {
            expect(ApiParsing::parseBoolean('yes'))->toBeTrue();
        });

        it('returns true for non-empty string', function () {
            expect(ApiParsing::parseBoolean('active'))->toBeTrue();
        });
    });

    describe('parseTimestamp()', function () {
        it('returns null for empty timestamp', function () {
            expect(ApiParsing::parseTimestamp(null))->toBeNull();
            expect(ApiParsing::parseTimestamp(''))->toBeNull();
            expect(ApiParsing::parseTimestamp(0))->toBeNull();
        });

        it('parses valid unix timestamp', function () {
            $timestamp = 1609459200; // 2021-01-01 00:00:00 UTC
            $result = ApiParsing::parseTimestamp($timestamp);

            expect($result)->toBeInstanceOf(CarbonInterface::class);
            expect($result->year)->toBe(2021);
            expect($result->month)->toBe(1);
            expect($result->day)->toBe(1);
        });

        it('parses string timestamp', function () {
            $timestamp = '1609459200';
            $result = ApiParsing::parseTimestamp($timestamp);

            expect($result)->toBeInstanceOf(CarbonInterface::class);
            expect($result->year)->toBe(2021);
        });
    });

    describe('parseDateTime()', function () {
        it('returns null for empty datetime', function () {
            expect(ApiParsing::parseDateTime(null))->toBeNull();
            expect(ApiParsing::parseDateTime(''))->toBeNull();
        });

        it('parses valid datetime string', function () {
            $datetime = '2021-06-15 14:30:00';
            $result = ApiParsing::parseDateTime($datetime);

            expect($result)->toBeInstanceOf(CarbonInterface::class);
            expect($result->year)->toBe(2021);
            expect($result->month)->toBe(6);
            expect($result->day)->toBe(15);
            expect($result->hour)->toBe(14);
            expect($result->minute)->toBe(30);
        });

        it('parses date-only string', function () {
            $datetime = '2021-12-25';
            $result = ApiParsing::parseDateTime($datetime);

            expect($result)->toBeInstanceOf(CarbonInterface::class);
            expect($result->year)->toBe(2021);
            expect($result->month)->toBe(12);
            expect($result->day)->toBe(25);
        });

        it('parses ISO 8601 format', function () {
            $datetime = '2021-06-15T14:30:00Z';
            $result = ApiParsing::parseDateTime($datetime);

            expect($result)->toBeInstanceOf(CarbonInterface::class);
            expect($result->year)->toBe(2021);
            expect($result->month)->toBe(6);
            expect($result->day)->toBe(15);
        });
    });
});
