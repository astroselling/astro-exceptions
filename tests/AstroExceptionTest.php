<?php

use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Astroselling\AstroExceptions\Exceptions\AstroException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

it('constructs with default values', function () {
    $exception = new AstroException(new Exception('Test exception'));

    expect($exception->getMessage())->toBe('Test exception');
    expect($exception->getCode())->toBe(0);
    expect($exception->getContext())->toBe([]);
    expect($exception->getType())->toBe(AstroExceptionTypeEnum::UNKNOWN);
});

it('constructs with provided values', function () {
    $context = ['key' => 'value'];
    $type = AstroExceptionTypeEnum::KNOWN;
    $exception = new AstroException(new Exception('Test exception', 123), $context, $type);

    expect($exception->getMessage())->toBe('Test exception');
    expect($exception->getCode())->toBe(123);
    expect($exception->getContext())->toBe($context);
    expect($exception->getType())->toBe($type);
});

it('adds context', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $exception->addContext('key', 'value');

    expect($exception->getContext())->toBe(['key' => 'value']);
});

it('sets type', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $exception->setType(AstroExceptionTypeEnum::KNOWN);

    expect($exception->getType())->toBe(AstroExceptionTypeEnum::KNOWN);
});

it('reports known exception to CloudWatch', function () {
    Log::shouldReceive('channel')->with('errors')->andReturnSelf();
    Log::shouldReceive('error')->once();

    $exception = new AstroException(new Exception('Test exception'), [], AstroExceptionTypeEnum::KNOWN);
    $result = $exception->report();

    expect($result)->toBeTrue();
});

it('does not report unknown exception to CloudWatch', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $result = $exception->report();

    expect($result)->toBeFalse();
});

it('renders json response', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $response = $exception->render();

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(500);
    expect($response->getData(true))->toBe(['error' => ['message' => 'An error occurred']]);
});
