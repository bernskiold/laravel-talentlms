<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\User;
use Bernskiold\LaravelTalentLms\Facades\TalentLms;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('talentlms.api', [
        'api_key' => 'secret-key',
        'domain' => 'https://example.talentlms.com',
        'version' => '1',
    ]);
});

it('fetches and parses a user', function () {
    Http::fake([
        'example.talentlms.com/api/v1/users/id:5' => Http::response([
            'id' => '5',
            'login' => 'jdoe',
            'email' => 'jdoe@example.com',
            'restrict_email' => '0',
            'created_on_timestamp' => '1700000000',
            'groups' => [['id' => '2', 'name' => 'Staff']],
        ]),
    ]);

    $user = TalentLms::users()->get(5);

    expect($user)->toBeInstanceOf(User::class)
        ->id->toBe(5)
        ->username->toBe('jdoe')
        ->restrictEmail->toBeFalse()
        ->and($user->createdAt->getTimestamp())->toBe(1700000000)
        ->and($user->groups)->toHaveCount(1);

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Basic '.base64_encode('secret-key:')));
});

it('looks up a user by email with the query in the path', function () {
    Http::fake([
        'example.talentlms.com/api/v1/users/email:jdoe@example.com' => Http::response([
            ['id' => '7', 'email' => 'jdoe@example.com'],
        ]),
    ]);

    expect(TalentLms::users()->lookupByEmail('jdoe@example.com'))
        ->id->toBe(7);
});

it('returns null when an email lookup fails', function () {
    Http::fake(['*' => Http::response(['error' => 'not found'], 404)]);

    expect(TalentLms::users()->lookupByEmail('missing@example.com'))->toBeNull();
});
