<?php

declare(strict_types=1);

namespace Marshal\Apps\Handler; 

use Psr\Container\ContainerInterface;

final class AppsHandlerFactory
{
    public function __invoke(ContainerInterface $container): AppsHandler
    {
        $databasesConfig = $container->get('config')['apps'] ?? [];
        return new AppsHandler($container, $databasesConfig);
    }
}
