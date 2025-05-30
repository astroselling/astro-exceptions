<?php

// config for Astroselling/AstroExceptions
return [
    'channels' => [
        'errors' => 'errors',
    ],
    'logging' => [
        'driver' => env('ASTRO_EXCEPTIONS_LOGGING_DRIVER', 'cloudwatch'),
    ],
];
