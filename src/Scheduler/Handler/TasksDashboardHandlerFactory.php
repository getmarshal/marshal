<?php

declare(strict_types=1);

namespace Marshal\Scheduler\Handler;

use Psr\Container\ContainerInterface;

final class TasksDashboardHandlerFactory
{
    public function __invoke(ContainerInterface $container): TasksDashboardHandler
    {
        return new TasksDashboardHandler();
    }
}
