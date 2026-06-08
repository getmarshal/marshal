<?php

declare(strict_types=1);

namespace Marshal\Authentication;

use Marshal\Authentication\Handler\AuthenticationHandler;
use Marshal\Authentication\User\User;
use Marshal\Authentication\User\UserInterface;
use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Mezzio\Helper\UrlHelperInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    use ServerRequestHelperTrait;

    public const string AUTHENTICATION_ATTRIBUTE = "marshal::authentication";

    private $userFactory;

    public function __construct(private UrlHelperInterface $urlHelper, callable $userFactory)
    {
        $this->userFactory = static function (array $details) use ($userFactory): UserInterface {
            return $userFactory($details);
        };
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if (! $session instanceof SessionInterface) {
            return $this->unauthenticatedResponse($request, $handler);
        }

        $details = $session->get(UserInterface::class);
        if (! \is_array($details)) {
            return $this->unauthenticatedResponse($request, $handler);
        }

        $user = ($this->userFactory)($details);
        if (! $user->isLoggedIn()) {
            return $this->unauthenticatedResponse($request, $handler);
        }
        
        return $handler->handle($request->withAttribute(UserInterface::class, $user));
    }

    private function getUnauthenticatedRedirect(ServerRequestInterface $request): string
    {
        return $this->urlHelper->generate(
            AuthenticationHandler::LOGIN_PAGE,
            queryParams: ["next" => $request->getUri()->getPath()]
        );
    }

    private function unauthenticatedResponse(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // if the request is headed to the login page
        if ($request->getUri()->getPath() === $this->urlHelper->generate(AuthenticationHandler::LOGIN_PAGE)) {
            return $handler->handle($request);
        }

        $platform = $this->getRequestPlatform($request);
        return $platform->redirectResponse($this->getUnauthenticatedRedirect($request));
    }
}
