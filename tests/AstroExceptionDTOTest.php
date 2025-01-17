<?php

use Astroselling\AstroExceptions\DTO\AstroExceptionDTO;
use Astroselling\AstroExceptions\Exceptions\AstroException;
use Illuminate\Http\JsonResponse;

it('can create an AstroExceptionDTO from exception', function () {
    $exception = new AstroException(new Exception('Test Exception'));

    $dto = (new AstroExceptionDTO)->fromException($exception);

    expect($dto)->toBeInstanceOf(AstroExceptionDTO::class);
});

it('can create an AstroExceptionDTO from Response', function () {
    $exception = new AstroException(new Exception('Test Exception'));

    $dto = (new AstroExceptionDTO)->fromResponse($exception->render()->getData(true));

    expect($dto)->toBeInstanceOf(AstroExceptionDTO::class);
});

it('returns jsonResponse when calling toResponse', function () {
    $exception = new AstroException(new Exception('Test Exception'));

    $dto = (new AstroExceptionDTO)->fromException($exception);

    expect($dto->toResponse())->toBeInstanceOf(JsonResponse::class)
        ->and($dto->toResponse()->getData(true))->toBe($dto->toArray())
        ->and($dto->toResponse()->getStatusCode())->toBe(500);
});
