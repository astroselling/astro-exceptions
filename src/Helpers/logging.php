<?php

if (! function_exists('getCloudWatchLogConfig')) {
    /**
     * @return string[]
     */
    function getCloudWatchLogConfig(string $streamName, ?int $retention = null, ?string $level = null, ?int $batchSize = null): array
    {
        $cloudwatchLoggerFactory = 'Astroselling\LaravelCloudwatchLogging\CloudWatchLoggerFactory';

        if (! class_exists($cloudwatchLoggerFactory)) {
            throw new \Exception('CloudWatch logging is not available.');
        }

        return [
            'driver' => 'custom',
            'via' => $cloudwatchLoggerFactory,
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

if (! function_exists('getAxiomLogConfig')) {
    /**
     * @return string[]
     */
    function getAxiomLogConfig(string $dataset, ?string $token = null, ?string $level = null): array
    {
        $axiomLogHandler = 'Jplhomer\Axiom\AxiomLogHandler';

        if (! class_exists($axiomLogHandler)) {
            throw new \Exception('Axiom logging is not available.');
        }

        return [
            'driver' => 'monolog',
            'handler' => $axiomLogHandler,
            'token' => $token ?? env('AXIOM_TOKEN'),
            'dataset' => $dataset,
            'level' => $level ?? env('AXIOM_LOG_LEVEL', env('LOG_LEVEL', 'debug')),
            'with' => [
                'apiToken' => env('AXIOM_API_TOKEN'),
                'dataset' => env('AXIOM_DATASET'),
            ],
        ];
    }
}
