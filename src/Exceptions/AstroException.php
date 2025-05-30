<?php

namespace Astroselling\AstroExceptions\Exceptions;

use Astroselling\AstroExceptions\DTO\AstroExceptionDTO;
use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AstroException extends Exception
{
    protected \Throwable $exception;

    /**
     * @var array<string, mixed>
     */
    protected array $context;

    protected AstroExceptionTypeEnum $type;

    protected string $integrationName;

    /**
     * @param  array<string, mixed>|null  $context
     */
    public function __construct(\Throwable $e, ?array $context = [], ?AstroExceptionTypeEnum $type = AstroExceptionTypeEnum::UNKNOWN, $integrationName = '')
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());

        $this->exception = $e;
        $this->context = $context ?? [];
        $this->type = $type ?? AstroExceptionTypeEnum::UNKNOWN;
        $this->integrationName = $integrationName;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    public function addContext(string $key, mixed $value): self
    {
        $this->context[$key] = $value;

        return $this;
    }

    public function addContexts(array $array): self
    {
        $this->context += $array;

        return $this;
    }

    public function setType(AstroExceptionTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): AstroExceptionTypeEnum
    {
        return $this->type;
    }

    public function getIntegrationName(): string
    {
        return $this->integrationName;
    }

    public function setIntegrationName(string $integrationName): self
    {
        $this->integrationName = $integrationName;

        return $this;
    }

    public function report(): bool
    {
        $this->addContexts((new AstroExceptionDTO)->fromException($this)->toArray());

        if ($this->type === AstroExceptionTypeEnum::KNOWN) {
            $this->reportToLogChannel();

            return true;
        }

        return false;
    }

    public function render(): JsonResponse
    {
        return (new AstroExceptionDTO)->fromException($this)->toResponse();
    }

    private function reportToLogChannel(): void
    {
        try {
            Log::channel(config('astro-exceptions.channels.errors', 'errors'))
                ->error($this->getMessage(), $this->context);
        } catch (\Throwable $e) {
            Log::error($this->getMessage(), $this->context);
        }
    }
}
