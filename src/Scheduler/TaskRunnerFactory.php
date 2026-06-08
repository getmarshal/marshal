<?php

declare(strict_types=1);

namespace Marshal\Scheduler;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class TaskRunnerFactory
{
    public function __invoke(ContainerInterface $container): TaskRunner
    {
        $transport = $container->get(TransportInterface::class);
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        return new TaskRunner($transport, $eventDispatcher);
    }
}
