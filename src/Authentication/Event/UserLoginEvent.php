<?php

declare(strict_types=1);

namespace Marshal\Authentication\Event;

use Marshal\Authentication\User\UserInterface;

final class UserLoginEvent
{
    public function __construct(private readonly UserInterface $user)
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
