<?php

declare(strict_types=1);

namespace Marshal\EventManager;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class EventDispatcherFactory
{
    public function __invoke(ContainerInterface $container): EventDispatcher
    {
        $listenerProvider = $container->get(ListenerProviderInterface::class);
        \assert($listenerProvider instanceof ListenerProviderInterface);

        return new EventDispatcher($listenerProvider);
    }
}
