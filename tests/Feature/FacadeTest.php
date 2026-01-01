<?php

use Bernskiold\LaravelTalentLms\Api\Resources\Courses;
use Bernskiold\LaravelTalentLms\Api\Resources\Groups;
use Bernskiold\LaravelTalentLms\Api\Resources\Users;
use Bernskiold\LaravelTalentLms\Facades\TalentLms;

describe('TalentLms Facade', function () {
    it('can access courses resource', function () {
        $courses = TalentLms::courses();

        expect($courses)->toBeInstanceOf(Courses::class);
    });

    it('can access users resource', function () {
        $users = TalentLms::users();

        expect($users)->toBeInstanceOf(Users::class);
    });

    it('can access groups resource', function () {
        $groups = TalentLms::groups();

        expect($groups)->toBeInstanceOf(Groups::class);
    });
});
