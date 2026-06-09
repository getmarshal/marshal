<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class AuthenticationHandlerFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationHandler
    {
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        return new AuthenticationHandler($eventDispatcher);
    }
}
