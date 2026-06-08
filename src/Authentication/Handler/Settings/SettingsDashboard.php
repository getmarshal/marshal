<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler\Settings;

use Marshal\Platform\PlatformInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SettingsDashboard implements RequestHandlerInterface
{
    public const string ROUTE_NAME = "marshal::settings-dashboard";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        return $platform->formatResponse($request);
    }
}
