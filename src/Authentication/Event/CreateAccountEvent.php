<?php

declare(strict_types=1);

namespace Marshal\Authentication\Event;

use Marshal\Auth\AccountInterface;
use Marshal\EventManager\ErrorMessagesTrait;

final class CreateAccountEvent
{
    use ErrorMessagesTrait;

    private bool $isSuccess = FALSE;

    public function __construct(private AccountInterface $account)
    {
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function setIsSuccess(bool $isSuccess): static
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }
}
