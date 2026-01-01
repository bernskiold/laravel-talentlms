<?php

use Bernskiold\LaravelTalentLms\Data\ListResponse;
use Illuminate\Support\Collection;

describe('ListResponse', function () {
    it('can be instantiated with null', function () {
        $response = new ListResponse(null);

        expect($response->get())->toBeInstanceOf(Collection::class);
        expect($response->get())->toHaveCount(0);
    });

    it('can be instantiated with an empty array', function () {
        $response = new ListResponse([]);

        expect($response->get())->toBeInstanceOf(Collection::class);
        expect($response->get())->toHaveCount(0);
    });

    it('can be instantiated with an array of items', function () {
        $items = ['item1', 'item2', 'item3'];
        $response = new ListResponse($items);

        expect($response->get())->toBeInstanceOf(Collection::class);
        expect($response->get())->toHaveCount(3);
        expect($response->get()->first())->toBe('item1');
    });

    it('can be instantiated with a Collection', function () {
        $collection = collect(['item1', 'item2']);
        $response = new ListResponse($collection);

        expect($response->get())->toBeInstanceOf(Collection::class);
        expect($response->get())->toHaveCount(2);
        expect($response->get())->toBe($collection);
    });

    it('returns the same collection on multiple get() calls', function () {
        $response = new ListResponse(['item1', 'item2']);

        $firstCall = $response->get();
        $secondCall = $response->get();

        expect($firstCall)->toBe($secondCall);
    });

    it('handles objects in the array', function () {
        $items = [
            (object) ['id' => 1, 'name' => 'Item 1'],
            (object) ['id' => 2, 'name' => 'Item 2'],
        ];
        $response = new ListResponse($items);

        expect($response->get())->toHaveCount(2);
        expect($response->get()->first()->id)->toBe(1);
    });
});
