<?php

use Astroselling\AstroExceptions\Facades\Sentry;

it('returns HubInterface as facade accessor', function () {
    Sentry::spy();

    Sentry::captureMessage('test');

    Sentry::shouldHaveReceived('captureMessage')->once();
});
