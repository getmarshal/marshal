<?php

declare(strict_types=1);

namespace Marshal\Apps\Middleware;

use Marshal\Apps\App;
use Marshal\Apps\Handler\AppSchemaHandler;
use Marshal\Apps\Handler\AppSchemaTypeHandler;
use Marshal\Apps\Handler\AppsHandler;
use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AppMiddleware implements MiddlewareInterface
{
    use ServerRequestHelperTrait;

    public const string APP_ATTRIBUTE = "marshal::app";

    public function __construct(private array $config)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $this->getRouteResult($request);
        if (
            AppsHandler::APP_DASHBOARD === $routeResult->getMatchedRouteName()
            || AppsHandler::APP_CONTENT_TYPE === $routeResult->getMatchedRouteName()
        ) {
            $appIdentifier = $routeResult->getMatchedParams()["app"];
            $appConfig = $this->appTagExists($appIdentifier);
            if (! $appConfig) {
                return $this->getRequestPlatform($request)->notFoundResponse($request);
            }
        } else {
            $appIdentifier = "marshal::default";
            $appConfig = $this->config[$appIdentifier];
        }

        $app = new App($appIdentifier, $appConfig);
        return $handler->handle($request->withAttribute(self::APP_ATTRIBUTE, $app));
    }

    private function appTagExists(string $tag): ?array
    {
        foreach ($this->config as $config) {
            if (isset($config['tag']) && $tag === $config['tag']) {
                return $config;
            }
        }

        return null;
    }
}
