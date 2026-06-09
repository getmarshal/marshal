<?php

declare(strict_types=1);

namespace Marshal\Authentication\Handler;

use Marshal\Authentication\Event\UserLoginEvent;
use Marshal\Authentication\User\User;
use Marshal\Authentication\User\UserInterface;
use Marshal\Database\Schema\ContentManager;
use Marshal\Platform\PlatformInterface;
use Marshal\Utils\Helper\ServerRequestHelperTrait;
use Marshal\Utils\Logger\LoggerManager;
use Mezzio\Router\RouteResult;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthenticationHandler implements RequestHandlerInterface
{
    use ServerRequestHelperTrait;

    public const string LOGIN_PAGE = "marshal::login";
    public const string HANDLE_LOGOUT = 'auth::logout';
    public const string TEMPLATE_LOGIN_PAGE = "marshal::login-page";

    public function __construct(private EventDispatcherInterface $eventDispatcher)
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

            $user = ContentManager::get(User::class);
            \assert($user instanceof UserInterface);

            try {
                $this->eventDispatcher->dispatch(new UserLoginEvent($user));
            } catch (\Throwable $e) {
                LoggerManager::get()->error($e->getMessage());
            }

            $next = "/";
            if (isset($request->getQueryParams()['next'])) {
                $next = $request->getQueryParams()['next'];
            }

            return $platform->redirectResponse($next);
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
