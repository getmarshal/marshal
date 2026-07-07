<?php

declare(strict_types=1);

namespace Marshal\Utils\Helper;

use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;
use Marshal\Platform\PlatformInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;

trait ServerRequestHelperTrait
{
    private function getRequestPlatform(ServerRequestInterface $request): PlatformInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        return $platform;
    }

    public function getRouteResult(ServerRequestInterface $request): RouteResult
    {
        return $request->getAttribute(RouteResult::class);
    }

    private function getSession(ServerRequestInterface $request): SessionInterface
    {
        return $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
    }
}
