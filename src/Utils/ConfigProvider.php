<?php

declare(strict_types=1);

namespace Marshal\Utils;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            "loggers" => $this->getLoggers(),
            "python" => $this->getPythonConfig(),
        ];
    }

    private function getLoggers(): array
    {
        return [
            "marshal::default" => [
                "handlers" => [
                    \Monolog\Handler\ErrorLogHandler::class => [],
                    \Monolog\Handler\StreamHandler::class => ['stream' => 'php://stdout'],
                ],
                "processors" => [
                    \Monolog\Processor\PsrLogMessageProcessor::class => [],
                ],
            ],
        ];
    }

    private function getPythonConfig(): array
    {
        return [
            "paths" => [],
            "scripts" => [],
        ];
    }
}
