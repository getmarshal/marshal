<?php

declare(strict_types=1);

namespace Marshal\Platform\Middleware;

use Psr\Container\ContainerInterface;

final class NotFoundResponseMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): NotFoundResponseMiddleware
    {
        return new NotFoundResponseMiddleware();
    }
}
