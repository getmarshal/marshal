<?php

declare(strict_types=1);

namespace Marshal\Apps\Middleware;

use Psr\Container\ContainerInterface;

final class AppMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AppMiddleware
    {
        $config = $container->get("config")["apps"] ?? [];
        return new AppMiddleware($config);
    }
}
