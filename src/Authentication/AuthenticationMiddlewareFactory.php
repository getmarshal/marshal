<?php

declare(strict_types=1);

namespace Marshal\Authentication;

use Marshal\Authentication\User\UserInterface;
use Mezzio\Helper\UrlHelperInterface;
use Psr\Container\ContainerInterface;

final class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationMiddleware
    {
        $urlHelper = $container->get(UrlHelperInterface::class);
        $userFactory = $container->get(UserInterface::class);
        return new AuthenticationMiddleware($urlHelper, $userFactory);
    }
}
