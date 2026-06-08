<?php

declare(strict_types=1);

namespace Marshal\Authentication\User;

use Marshal\Database\Schema\Content;

final class User extends Content implements UserInterface
{
    public function getCredential(): string
    {
        return $this->getPropertyValue(UserInterface::USER_CREDENTIAL);
    }

    public function getRoles(): array
    {
        return $this->getPropertyValue(UserInterface::USER_ROLES);
    }

    public function getStatus(): string
    {
        return $this->getPropertyValue(UserInterface::USER_STATUS);
    }

    public function isLoggedIn(): bool
    {
        return true;
    }
}
