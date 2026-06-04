<?php

declare(strict_types=1);

namespace Marshal\Search\Handler;

use Marshal\Platform\PlatformInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SearchHandler implements RequestHandlerInterface
{
    public const string ROUTE_NAME = "marshal::search";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        $query = $request->getQueryParams()['q'] ?? null;
        if (null === $query) {
            return $platform->formatResponse($request);
        }

        return $platform->formatResponse($request, [
            'query' => $query,
        ]);
    }
}
