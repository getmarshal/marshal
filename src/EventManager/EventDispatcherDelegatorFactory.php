<?php

declare(strict_types=1);

namespace Marshal\EventManager;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, callable $callback): object
    {
        $instance = $callback();
        if (! $instance instanceof EventDispatcherAwareInterface) {
            throw new \RuntimeException(\sprintf(
                "%s must implement %s",
                \get_debug_type($instance),
                EventDispatcherAwareInterface::class
            ));
        }

        $instance->setEventDispatcher($container->get(EventDispatcherInterface::class));
        return $instance;
    }
}
