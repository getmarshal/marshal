<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler\Settings\Extensions;

use Marshal\Platform\PlatformInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ExtensionsDashboard implements RequestHandlerInterface
{
    public const string ROUTE_NAME = "marshal::extensions-dashboard";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        return $platform->formatResponse($request);
    }
}
