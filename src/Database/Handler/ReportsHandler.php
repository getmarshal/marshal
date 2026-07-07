<?php

declare(strict_types=1);

namespace Marshal\Database\Handler;

use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ReportsHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string REPORTS_DASHBOARD = "marshal::reports-dashboard";
    public const string SINGLE_REPORT = "marshal::single-report";

    public function __construct(private ContainerInterface $container, private array $reportsConfig)
    {
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeResult = $this->getRouteResult($request);
        if ($routeResult->getMatchedRouteName() === self::SINGLE_REPORT) {
            return $this->handleSingleReport($request);
        }

        $platform = $this->getRequestPlatform($request);
        return $platform->formatResponse($request, [
            "reports" => $this->reportsConfig,
        ], self::REPORTS_DASHBOARD);
    }

    private function handleSingleReport(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $this->getRequestPlatform($request);
        $routeResult = $this->getRouteResult($request);
        $routeParams = $routeResult->getMatchedParams();
        $schema = $routeParams['schema'];
        $report = $routeParams['report'];
        if (! isset($this->reportsConfig[$schema][$report])) {
            return $platform->notFoundResponse($request);
        }
        
        $reportDetails = $this->reportsConfig[$schema][$report];
        if (! isset($reportDetails['handler']) || ! \is_string($reportDetails['handler'])) {
            return $platform->notFoundResponse($request);
        }

        $handler = $this->container->get($reportDetails['handler']);
        if (! $handler instanceof RequestHandlerInterface) {
            return $platform->notFoundResponse($request);
        }

        return $handler->handle($request);
    }
}
