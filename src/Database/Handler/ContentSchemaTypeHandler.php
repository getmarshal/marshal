<?php

declare(strict_types=1);

namespace Marshal\Database\Handler;

use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Marshal\Database\Schema\ContentManager;

final class ContentSchemaTypeHandler implements RequestHandlerInterface
{
    use ContentHandlerTrait;
    use ServerRequestHelperTrait;

    public const string ROUTE_CONTENT_SCHEMA_TYPE = "marshal::content-schema-type";

    public function __construct(
        private ContainerInterface $container,
        private array $databaseConfig,
        private array $schemaConfig
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $this->getRequestPlatform($request);
        $schema = $request->getAttribute('schema');
        $type = $request->getAttribute('type');

        $selectedDbName = $this->getSchemaName($schema, $this->databaseConfig);
        $selectedDbConfig = $this->getSchemaConfig($schema, $this->databaseConfig);
        $selectedSchemaType = $this->getSelectedSchemaType($type, $this->schemaConfig);

        if (null === $selectedDbName || empty($selectedDbConfig) || empty($selectedSchemaType)) {
            return $platform->notFoundResponse($request);
        }

        $content = ContentManager::get($selectedSchemaType);
        $handler = $content->getHandler();
        if (null === $handler) {
            return $platform->notFoundResponse($request);
        }

        $handlerInstance = $this->container->get($handler);
        \assert($handlerInstance instanceof RequestHandlerInterface);

        return $handlerInstance->handle($request);
    }
}
