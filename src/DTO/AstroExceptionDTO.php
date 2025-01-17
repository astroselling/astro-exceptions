<?php

namespace Astroselling\AstroExceptions\DTO;

use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Astroselling\AstroExceptions\Exceptions\AstroException;
use Illuminate\Http\JsonResponse;


class AstroExceptionDTO
{
    protected string $integrationName;
    protected string $integrationMessage;
    protected int $integrationErrorCode;
    protected AstroExceptionTypeEnum $integrationType;
    protected int $selfErrorCode;

    public function __construct(AstroException $exception)
    {
        $this->integrationName = $exception->getIntegrationName();
        $this->integrationMessage = $exception->getMessage();
        $this->integrationErrorCode = (int) $exception->getCode();
        $this->integrationType = $exception->getType();
        $this->selfErrorCode = $exception->getType() === AstroExceptionTypeEnum::KNOWN ? 424 : 500;
    }

    public function toArray(): array
    {
        return [
            'integration_name' => $this->integrationName,
            'integration_message' => $this->integrationMessage,
            'integration_error_code' => $this->integrationErrorCode,
            'integration_type' => $this->integrationType->value,
            'self_error_code' => $this->selfErrorCode,
        ];
    }

    public function fromResponse(array $response): AstroExceptionDTO
    {
        $this->integrationErrorCode = $response['integration_error_code'];
        $this->integrationName = $response['integration_name'];
        $this->integrationMessage = $response['integration_message'];
        $this->integrationType = $response['integration_type'];
        $this->selfErrorCode = $response['self_error_code'];
        return $this;
    }

    public function toResponse(): JsonResponse
    {
        return response()->json($this->toArray(), $this->selfErrorCode);
    }
}
