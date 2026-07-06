<?php

use App\Support\ParticipantExportSupport;

uses(Tests\TestCase::class);

test('export filename slug falls back when tournament is missing', function () {
    expect(ParticipantExportSupport::exportFileNameSlug(null))->toBe('all');
});
