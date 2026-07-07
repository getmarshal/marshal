<?php

declare(strict_types=1);

namespace Marshal\Form\Handler;

use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string INDEX_PAGE = "marshal::forms-index";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $this->getRequestPlatform($request);
        return $platform->formatResponse($request, template: self::INDEX_PAGE);
    }
}
