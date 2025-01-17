<?php

use Astroselling\AstroExceptions\DTO\AstroExceptionDTO;
use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Astroselling\AstroExceptions\Exceptions\AstroException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

it('constructs with default values', function () {
    $exception = new AstroException(new Exception('Test exception'));

    expect($exception->getMessage())->toBe('Test exception')
        ->and($exception->getCode())->toBe(0)
        ->and($exception->getContext())->toBe([])
        ->and($exception->getType())->toBe(AstroExceptionTypeEnum::UNKNOWN);
});

it('constructs with provided values', function () {
    $context = ['key' => 'value'];
    $type = AstroExceptionTypeEnum::KNOWN;
    $exception = new AstroException(new Exception('Test exception', 123), $context, $type);

    expect($exception->getMessage())->toBe('Test exception')
        ->and($exception->getCode())->toBe(123)
        ->and($exception->getContext())->toBe($context)
        ->and($exception->getType())->toBe($type);
});

it('adds context', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $exception->addContext('key', 'value');

    expect($exception->getContext())->toBe(['key' => 'value']);
});

it('adds contexts', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $exception->addContext('key', 'value');

    $exception->addContexts(['key2' => 'value2']);

    expect($exception->getContext())->toBe(['key' => 'value', 'key2' => 'value2']);
});

it('gets and set integration name', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $exception->setIntegrationName('testIntegrationName');

    expect($exception->getIntegrationName())->toBe('testIntegrationName');
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

it('renders json unknown response', function () {
    $exception = new AstroException(new Exception('Test exception'));
    $dto = (new AstroExceptionDTO($exception));

    $response = $exception->render();

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(500)
        ->and($response->getData(true))->toBe($dto->toArray());
});

it('renders json known response', function () {
    $exception = new AstroException(new Exception('Test exception'), [], AstroExceptionTypeEnum::KNOWN, 'tiendaNube');
    $dto = (new AstroExceptionDTO($exception));

    $response = $exception->render();

    expect($response)->toBeInstanceOf(JsonResponse::class)
        ->and($response->getStatusCode())->toBe(424)
        ->and($response->getData(true))->toBe($dto->toArray());
});
