<?php

/**
 *
 */

declare(strict_types=1);

namespace Marshal\Utils\Logger;

use Marshal\Utils\Config;
use Monolog\Logger;
use Monolog\Handler\HandlerInterface;
use Monolog\Processor\ProcessorInterface;
use Psr\Log\LoggerInterface;

final class LoggerManager
{
    private static array $loggers = [];

    private function __construct()
    {
    }

    private function __clone(): void
    {
    }

    public static function get(string $name = "marshal::default"): LoggerInterface
    {
        if (isset(static::$loggers[$name]) && static::$loggers[$name] instanceof LoggerInterface) {
            return static::$loggers[$name];
        }

        $loggersConfig = Config::get('loggers');

        // validate this logger
        $validator = new Validator\LoggerConfigValidator($loggersConfig);
        if (! $validator->isValid($name)) {
            throw new Exception\InvalidLoggerConfigException($name, $validator->getMessages());
        }

        $config = $loggersConfig[$name];
        $logger = new Logger($name);

        // push handlers
        foreach ($config['handlers'] ?? [] as $handler => $handlerOptions) {
            try {
                $instance = new $handler(...$handlerOptions);
                if (! $instance instanceof HandlerInterface) {
                    throw new \InvalidArgumentException(\sprintf(
                        "Logger handler %s for logger %s is invalid. Handlers must implement %s",
                        $handler,
                        $name,
                        HandlerInterface::class
                    ));
                }

            } catch (\Throwable $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }

            $logger->pushHandler($instance);
        }

        // push processors
        foreach ($config['processors'] ?? [] as $processor => $processorOptions) {
            try {
                $instance = new $processor(...$processorOptions);
                if (! $instance instanceof ProcessorInterface) {
                    throw new \InvalidArgumentException(\sprintf(
                        "Logger processor %s for logger %s invalid. Processors must implement %s",
                        $processor,
                        $name,
                        ProcessorInterface::class
                    ));
                }
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }

            $logger->pushProcessor($instance);
        }

        static::$loggers[$name] = $logger;

        return $logger;
    }
}
