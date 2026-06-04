<?php

declare(strict_types=1);

namespace Marshal\Utils\Logger\Validator;

use Laminas\Validator\AbstractValidator;

final class LoggerConfigValidator extends AbstractValidator
{
    public const string LOGGER_NOT_FOUND = "loggerNotFound";
    public const string INVALID_LOGGER_HANDLER = "invalidLoggerHandler";
    public const string INVALID_LOGGER_PROCESSOR = "invalidLoggerProcessor";
    public array $messageTemplates = [
        self::LOGGER_NOT_FOUND => "Logger %value% not found in config",
        self::INVALID_LOGGER_HANDLER => "Invalid handler %value% for logger",
        self::INVALID_LOGGER_PROCESSOR => "Invalid processor %value% for logger",
    ];

    public function __construct(private array $config)
    {
    }

    public function isValid(mixed $value): bool
    {
        if (! isset($this->config[$value])) {
            $this->setValue($value);
            $this->error(self::LOGGER_NOT_FOUND);
            return FALSE; // @todo check allow_continue.. value - whether it allows continue on failure
        }

        $config = $this->config[$value];
        foreach ($config['handlers'] ?? [] as $handler => $handlerOptions) {
            if (! \is_string($handler)) {
                $this->setValue(\get_debug_type($handler));
                $this->error(self::INVALID_LOGGER_HANDLER);
                return FALSE;
            }

            if (FALSE === $this->isValidHandlerOptions($handlerOptions)) {
                return FALSE;
            }
        }

        foreach ($config['processors'] ?? [] as $processor => $processorOptions) {
            if (! \is_string($processor)) {
                $this->setValue(\get_debug_type($processor));
                $this->error(self::INVALID_LOGGER_PROCESSOR);
                return FALSE;
            }

            if (FALSE === $this->isValidProcessorOptions($processorOptions)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    private function isValidHandlerOptions(array $options): bool
    {
        return TRUE;
    }

    private function isValidProcessorOptions(array $options): bool
    {
        return TRUE;
    }
}
