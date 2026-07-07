<?php

declare(strict_types=1);

namespace Marshal\System\Handler;

use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SystemHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string SYSTEM_PAGE = "marshal::system-page";

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $this->getRequestPlatform($request);
        return $platform->formatResponse($request, template: self::SYSTEM_PAGE);
    }
}
