<?php

declare(strict_types=1);

namespace Marshal\Apps\Handler;

use Marshal\Apps\App;
use Marshal\Apps\Middleware\AppMiddleware;
use Marshal\Database\Query\Select;
use Marshal\Database\Schema\Content;
use Marshal\Database\Schema\ContentForm;
use Marshal\Database\Schema\ContentManager;
use Marshal\Platform\PlatformInterface;
use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AppsHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string APPS_DASHBOARD = "marshal::apps-dashboard";
    public const string APP_DASHBOARD = "marshal::app-dashboard";
    public const string APP_CONTENT_TYPE = "marshal::app-content-type";

    public function __construct(private ContainerInterface $container, private array $appsConfig)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $this->getRequestPlatform($request);
        $routeResult = $this->getRouteResult($request);
        return match ($routeResult->getMatchedRouteName()) {
            self::APPS_DASHBOARD => $this->handleAppsDashboard($request, $platform),
            self::APP_DASHBOARD => $this->handleAppDashboard($request, $platform),
            self::APP_CONTENT_TYPE => $this->handleAppContentType($request, $platform),
            default => $platform->badRequestResponse($request)
        };
    }

    private function handleAppContentType(ServerRequestInterface $request, PlatformInterface $platform): ResponseInterface
    {
        $app = $request->getAttribute(AppMiddleware::APP_ATTRIBUTE);
        if (! $app instanceof App) {
            return $platform->badRequestResponse($request);
        }

        $routeResult = $this->getRouteResult($request);
        $type = $app->getContentSchemaType($routeResult->getMatchedParams()["type"]);
        if (null === $type) {
            return $platform->notFoundResponse($request);
        }

        $content = ContentManager::get($type);
        if (null === $content->getContentConfig()->getHandler()) {
            return $platform->notFoundResponse($request);
        }

        // if (null === $content->getContentConfig()->getHandler()) {
        //     return $this->handleContentView($request, $platform, $content);
        // }

        $handler = $this->container->get($content->getContentConfig()->getHandler());
        \assert($handler instanceof RequestHandlerInterface);

        return $handler->handle($request);
    }

    private function handleAppDashboard(ServerRequestInterface $request, PlatformInterface $platform): ResponseInterface
    {
        $app = $request->getAttribute(AppMiddleware::APP_ATTRIBUTE);
        if (! $app instanceof App) {
            return $platform->badRequestResponse($request);
        }

        $types = [];
        foreach ($app->getContentSchema() as $type) {
            if (! isset($type["tag"])) {
                continue;
            }

            $types[] = $type;
        }

        return $platform->formatResponse($request, [
            "app" => $app->toArray(),
            "types" => $types,
        ], self::APP_DASHBOARD);
    }

    private function handleAppsDashboard(ServerRequestInterface $request, PlatformInterface $platform): ResponseInterface
    {
        $apps = [];
        foreach ($this->appsConfig as $app => $config) {
            if ($app === "marshal::default") {
                continue;
            }

            $apps[$app] = [
                "label" => $config["label"],
                "description" => $config["description"],
                "tag" => $config["tag"],
            ];
        }
        return $platform->formatResponse($request, [
            'apps' => $apps,
        ], self::APPS_DASHBOARD);
    }

    private function handleContentView(ServerRequestInterface $request, PlatformInterface $platform, Content $content): ResponseInterface
    {
        $params = $request->getQueryParams();
        if (empty($params) || ! isset($params['tag'])) {
            return $this->handleContentViewIndex($request, $platform, $content);
        }

        // hydrate the content
        $content = $this->hydrateContent($request, $content);

        // check whether content was hydrated, i.e found
        if ($content->isEmpty()) {
            return $platform->notFoundResponse($request);
        }

        // view an edit page
        if (isset($params['action']) && $params['action'] === "edit" && 'GET' === \strtoupper($request->getMethod())) {
            return $this->handleContentEditView($request, $platform, $content);
        }

        // updating content
        if (isset($params['action']) && $params['action'] === "edit" && 'POST' === \strtoupper($request->getMethod())) {
            return $this->handleContentUpdate($request, $platform, $content);
        }

        $template = $content->getContentConfig()->hasViewTemplate()
            ? $content->getContentConfig()->getViewTemplate()
            : self::APP_CONTENT_TYPE;

        return $platform->formatResponse(
            $request,
            ["content" => $content->toArray()],
            $template
        );
    }

    private function handleContentViewIndex(ServerRequestInterface $request, PlatformInterface $platform, Content $content): ResponseInterface
    {
        $collection = Select::from($content->getSchemaIdentifier());
        $template = $content->getContentConfig()->hasIndexTemplate()
            ? $content->getContentConfig()->getIndexTemplate()
            : self::APP_CONTENT_TYPE;

        return $platform->formatResponse(
            $request,
            ["collection" => $collection->fetchAllLazy()],
            $template
        );
    }

    private function handleContentEditView(
        ServerRequestInterface $request,
        PlatformInterface $platform,
        Content $content
    ): ResponseInterface {
        $form = ContentForm::create($content);
        $form->setData($content->toArray());

        // @todo form handling

        return $platform->formatResponse($request);
    }

    private function handleContentUpdate(
        ServerRequestInterface $request,
        PlatformInterface $platform,
        Content $content
    ): ResponseInterface {
        $form = ContentForm::create($content);
        $form->setData($content->toArray());

        // @todo handle form

        return $platform->formatResponse($request);
    }

    private function hydrateContent(ServerRequestInterface $request, Content $content): Content
    {
        $select = Select::from($content->getSchemaIdentifier());
        $params = $request->getQueryParams();
        foreach ($params as $key => $value) {
            if (! $content->hasProperty($key)) {
                continue;
            }

            if ($content->isRelationProperty($key)) {
                continue;
            }

            $select->where($key, $value);
        }
        
        return $select->fetch();
    }
}
