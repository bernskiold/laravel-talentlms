<?php

use Bernskiold\LaravelTalentLms\Api\Resources\Courses;
use Bernskiold\LaravelTalentLms\Api\Resources\Groups;
use Bernskiold\LaravelTalentLms\Api\Resources\Users;
use Bernskiold\LaravelTalentLms\Api\TalentLms;
use Bernskiold\LaravelTalentLms\Api\TalentLmsApiClient;

describe('TalentLms', function () {
    beforeEach(function () {
        $this->client = new TalentLmsApiClient(
            apiKey: 'test-api-key',
            domain: 'https://test.talentlms.com',
            version: '1'
        );
        $this->talentLms = new TalentLms($this->client);
    });

    it('returns Courses resource', function () {
        expect($this->talentLms->courses())->toBeInstanceOf(Courses::class);
    });

    it('returns Users resource', function () {
        expect($this->talentLms->users())->toBeInstanceOf(Users::class);
    });

    it('returns Groups resource', function () {
        expect($this->talentLms->groups())->toBeInstanceOf(Groups::class);
    });
});
