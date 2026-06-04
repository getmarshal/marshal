<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime;

use Marshal\Server\Runtime\Apache2Handler\Apache2Handler;
use Marshal\Server\Runtime\Cli\CliRuntime;
use Psr\Container\ContainerInterface;

class RuntimeDetector
{
    private const array SUPPORTED_RUNTIMES = [
        'apache2handler',
        'cli'
    ];

    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * Detect the current PHP runtime environment
     */
    public function detectRuntime(): RuntimeInterface
    {
        $processManager = $this->detectProcessManager();
        if (! \in_array($processManager, self::SUPPORTED_RUNTIMES, TRUE)) {
            throw new \RuntimeException(\sprintf(
                "Unsupported runtime %s. Allowed runtimes include: %s",
                $processManager,
                \implode(", ", self::SUPPORTED_RUNTIMES)
            ));
        }

        return match ($processManager) {
            'apache2handler'    => $this->container->get(Apache2Handler::class),
            'cli'               => $this->container->get(CliRuntime::class)
        };
    }

    /**
     * Detect the process manager
     *
     * @return string Process manager or runtime name
     */
    private function detectProcessManager(): string
    {
        return match (\php_sapi_name()) {
            'cli-server', 'cli'     => 'cli',
            default                 => 'apache2handler',
        };

        // @todo expand to others
    }
}