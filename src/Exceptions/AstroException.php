<?php

namespace Astroselling\AstroExceptions\Exceptions;

use Astroselling\AstroExceptions\Enums\AstroExceptionTypeEnum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class AstroException extends Exception
{
    protected \Throwable $exception;

    /**
     * @var array<string, mixed>
     */
    protected array $context;

    protected AstroExceptionTypeEnum $type;

    /**
     * @param  array<string, mixed>|null  $context
     */
    public function __construct(\Throwable $e, ?array $context = [], ?AstroExceptionTypeEnum $type = AstroExceptionTypeEnum::UNKNOWN)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());

        $this->exception = $e;
        $this->context = $context ?? [];
        $this->type = $type ?? AstroExceptionTypeEnum::UNKNOWN;
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

    public function setType(AstroExceptionTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): AstroExceptionTypeEnum
    {
        return $this->type;
    }

    public function report(): bool
    {
        if ($this->type === AstroExceptionTypeEnum::KNOWN) {
            $this->reportToCloudWatch();

            return false;
        }

        return true;
    }

    public function render(): JsonResponse
    {
        return Response::json([
            'error' => [
                'message' => 'An error occurred',
            ],
        ], 500);
    }

    private function reportToCloudWatch(): void
    {
        try {
            Log::channel(config('astro-exceptions.channels.errors', 'errors'))
                ->error($this->getMessage(), $this->context);
        } catch (\Throwable $e) {
            Log::error($this->getMessage(), $this->context);
        }
    }
}
