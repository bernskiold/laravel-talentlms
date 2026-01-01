<?php

use Bernskiold\LaravelTalentLms\Data\ApiResources\Unit;

describe('Unit DTO', function () {
    it('can be instantiated with required parameters', function () {
        $unit = new Unit(
            id: 1,
            type: 'video',
            name: 'Introduction Video',
        );

        expect($unit)->toBeInstanceOf(Unit::class);
        expect($unit->id)->toBe(1);
        expect($unit->type)->toBe('video');
        expect($unit->name)->toBe('Introduction Video');
    });

    it('can be instantiated with all parameters', function () {
        $unit = new Unit(
            id: 1,
            type: 'document',
            name: 'Course Materials',
            url: 'https://example.com/document.pdf',
            aggregatedDelayTimeInMinutes: 30,
        );

        expect($unit->id)->toBe(1);
        expect($unit->type)->toBe('document');
        expect($unit->name)->toBe('Course Materials');
        expect($unit->url)->toBe('https://example.com/document.pdf');
        expect($unit->aggregatedDelayTimeInMinutes)->toBe(30);
    });

    it('can be created from API response', function () {
        $response = (object) [
            'id' => 1,
            'type' => 'test',
            'name' => 'Final Exam',
            'url' => 'https://example.com/test/1',
            'aggregated_delay_time' => 60,
        ];

        $unit = Unit::fromResponse($response);

        expect($unit->id)->toBe(1);
        expect($unit->type)->toBe('test');
        expect($unit->name)->toBe('Final Exam');
        expect($unit->url)->toBe('https://example.com/test/1');
        expect($unit->aggregatedDelayTimeInMinutes)->toBe(60);
    });

    it('handles missing optional fields', function () {
        $response = (object) [
            'id' => 1,
            'type' => 'video',
            'name' => 'Basic Video',
        ];

        $unit = Unit::fromResponse($response);

        expect($unit->id)->toBe(1);
        expect($unit->type)->toBe('video');
        expect($unit->name)->toBe('Basic Video');
        expect($unit->url)->toBeNull();
        expect($unit->aggregatedDelayTimeInMinutes)->toBeNull();
    });

    it('has readonly properties', function () {
        $unit = new Unit(id: 1, type: 'video', name: 'Test');

        expect(fn () => $unit->id = 2)->toThrow(Error::class);
    });
});
