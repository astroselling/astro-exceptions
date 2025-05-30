<?php

use Astroselling\AstroExceptions\Exceptions\AstroException;
use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Illuminate\Support\Facades\Log;

it('configures CloudWatch logging when driver is cloudwatch', function () {
    config(['astro-exceptions.logging.driver' => 'cloudwatch']);

    $streamName = 'test-stream';
    $config = getCloudWatchLogConfig($streamName);

    expect($config['driver'])->toBe('custom')
        ->and($config['via'])->toBe('Astroselling\LaravelCloudwatchLogging\CloudWatchLoggerFactory')
        ->and($config['streamName'])->toBe($streamName);
});

it('configures Axiom logging when driver is axiom', function () {
    config(['astro-exceptions.logging.driver' => 'axiom']);

    $dataset = 'test-dataset';
    $config = getAxiomLogConfig($dataset);

    expect($config['driver'])->toBe('monolog')
        ->and($config['dataset'])->toBe($dataset);
});

it('reports to log channel with CloudWatch driver', function () {
    config(['astro-exceptions.logging.driver' => 'cloudwatch']);
    config(['astro-exceptions.channels.errors' => 'cloudwatch-errors']);

    Log::shouldReceive('channel')->with('cloudwatch-errors')->andReturnSelf();
    Log::shouldReceive('error')->once();

    $exception = new AstroException(new Exception('Test exception'), [], AstroExceptionTypeEnum::KNOWN);
    $result = $exception->report();

    expect($result)->toBeTrue();
});

it('reports to log channel with Axiom driver', function () {
    config(['astro-exceptions.logging.driver' => 'axiom']);
    config(['astro-exceptions.channels.errors' => 'axiom-errors']);

    Log::shouldReceive('channel')->with('axiom-errors')->andReturnSelf();
    Log::shouldReceive('error')->once();

    $exception = new AstroException(new Exception('Test exception'), [], AstroExceptionTypeEnum::KNOWN);
    $result = $exception->report();

    expect($result)->toBeTrue();
});
