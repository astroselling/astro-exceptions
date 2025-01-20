<?php

namespace Astroselling\AstroExceptions\DTO;

use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Astroselling\AstroExceptions\Exceptions\AstroException;
use Illuminate\Http\JsonResponse;

class AstroExceptionDTO
{
    protected string $integrationName = '';

    protected string $integrationMessage = '';

    protected int $integrationErrorCode = 0;

    protected AstroExceptionTypeEnum $errorType = AstroExceptionTypeEnum::UNKNOWN;

    protected int $selfErrorCode = 500;

    public function fromException(AstroException $exception): self
    {
        $this->integrationName = $exception->getIntegrationName();
        $this->integrationMessage = $exception->getMessage();
        $this->integrationErrorCode = (int) $exception->getCode();
        $this->errorType = $exception->getType();
        $this->selfErrorCode = $exception->getType() === AstroExceptionTypeEnum::KNOWN ? 424 : 500;

        return $this;
    }

    public function fromResponse(array $response): AstroExceptionDTO
    {
        $this->integrationErrorCode = $response['integration_error_code'];
        $this->integrationName = $response['integration_name'];
        $this->integrationMessage = $response['integration_message'];
        $this->errorType = AstroExceptionTypeEnum::from($response['error_type']);
        $this->selfErrorCode = $response['self_error_code'];

        return $this;
    }

    public function toResponse(): JsonResponse
    {
        return response()->json($this->toArray(), $this->selfErrorCode);
    }

    public function toArray(): array
    {
        return [
            'integration_name' => $this->integrationName,
            'integration_message' => $this->integrationMessage,
            'integration_error_code' => $this->integrationErrorCode,
            'error_type' => $this->errorType->value,
            'self_error_code' => $this->selfErrorCode,
        ];
    }
}
