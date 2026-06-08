<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Marshal\Authentication\User\UserInterface;
use Marshal\Platform\PlatformInterface;
use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthenticationHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string LOGIN_PAGE = "marshal::login";
    public const string HANDLE_LOGOUT = 'auth::logout';
    public const string TEMPLATE_LOGIN_PAGE = "marshal::login-page";

    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        \assert($routeResult instanceof RouteResult);

        return match ($routeResult->getMatchedRouteName()) {
            self::LOGIN_PAGE => $this->handleLogin($request),
            self::HANDLE_LOGOUT => $this->handleLogout($request),
        };
    }

    private function handleLogin(ServerRequestInterface $request): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        if ('POST' === \strtoupper($request->getMethod())) {
            // $input = $request->getParsedBody();
            $session = $this->getSession($request);
            $session->set(UserInterface::class, [
                "name" => "root",
            ]);
            // var_dump($session->get(UserInterface::class));
            // $session->regenerate();

            return $platform->redirectResponse('/');
        }

        return $platform->formatResponse($request, template: self::TEMPLATE_LOGIN_PAGE);
    }

    private function handleLogout(ServerRequestInterface $request): ResponseInterface
    {
        $session = $this->getSession($request);
        $session->clear();

        $platform = $request->getAttribute(PlatformInterface::class);
        \assert($platform instanceof PlatformInterface);

        return $platform->redirectResponse('/');
    }
}
