<?php

declare(strict_types=1);

namespace Marshal\PythonBridge;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'python' => $this->getServerConfig(),
        ];
    }

    private function getServerConfig(): array
    {
        return [
            'host' => 'http://localhost:8000',
            'paths' => [
                'marshal' => [
                    __DIR__ . '/../app'
                ],
            ],
        ];
    }
}
