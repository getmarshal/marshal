<?php

declare(strict_types=1);

namespace Marshal\Database\Handler;

use Psr\Container\ContainerInterface;

final class ReportsHandlerFactory
{
    public function __invoke(ContainerInterface $container): ReportsHandler
    {
        $config = $container->get('config')['reports'] ?? [];
        return new ReportsHandler($container, $config);
    }
}
