<?php

declare(strict_types=1);

namespace Marshal\Utils\Trait;

trait ErrorMessagesTrait
{
    private array $errorMessages = [];

    public function getErrorMessage(string $identifier): string
    {
        if (! isset($this->errorMessages[$identifier])) {
            throw new \InvalidArgumentException(\sprintf(
                "Identifier %s not found in error messages",
                $identifier
            ));
        }

        return $this->errorMessages[$identifier];
    }

    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    public function hasErrorMessages(): bool
    {
        return empty($this->errorMessages) ? false : true;
    }

    public function setErrorMessage(string $identifier, string $message): static
    {
        $this->errorMessages[$identifier] = $message;
        return $this;
    }

    public function setErrorMessages(array $messages): static
    {
        $this->errorMessages = $messages;
        return $this;
    }
}
