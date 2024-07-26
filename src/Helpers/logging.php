<?php

use Astroselling\LaravelCloudwatchLogging\CloudWatchLoggerFactory;

if (! function_exists('getCloudWatchLogConfig')) {
    /**
     * @return string[]
     */
    function getCloudWatchLogConfig(string $streamName, ?int $retention = null, ?string $level = null, ?int $batchSize = null): array
    {
        return [
            'driver' => 'custom',
            'via' => CloudWatchLoggerFactory::class,
            'name' => config('app.name'),
            'sdk' => [
                'region' => env('AWS_DEFAULT_REGION', 'sa-east-1'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ],
            'streamName' => $streamName,
            'retention' => $retention ?? env('CLOUDWATCH_LOG_RETENTION', 14),
            'level' => $level ?? env('CLOUDWATCH_LOG_LEVEL', env('LOG_LEVEL', 'debug')),
            'batch_size' => $batchSize ?? env('CLOUDWATCH_BATCH_SIZE', 1000),
        ];
    }
}
