<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler;

use Psr\Container\ContainerInterface;

final class AuthenticationHandlerFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationHandler
    {
        return new AuthenticationHandler();
    }
}
