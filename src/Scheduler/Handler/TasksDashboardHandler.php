<?php

declare(strict_types=1);

namespace Marshal\Scheduler\Handler;

use Marshal\Platform\PlatformInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TasksDashboardHandler implements RequestHandlerInterface
{
    public const string DASHBOARD_HANDLER = "marshal::tasks-dashboard";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        return $platform->formatResponse($request);
    }
}
