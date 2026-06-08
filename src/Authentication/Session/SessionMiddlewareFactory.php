<?php

declare(strict_types= 1);

namespace Marshal\Authentication\Session;

use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration as JwtConfig;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Marshal\Utils\Config;
use Psr\Container\ContainerInterface;
use PSR7Sessions\Storageless\Http\ClientFingerprint\Configuration as ClientFingerprintConfiguration;
use PSR7Sessions\Storageless\Http\Configuration;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

final class SessionMiddlewareFactory
{
    private static array $cookieDefaults = [
        'http_only' => true,
        'name' => '__Secure-slsession',
        'path' => '/',
        'same_site' => 'lax',
        'secure' => true,
    ];

    public function __invoke(ContainerInterface $container): SessionMiddleware
    {
        $session = Config::get('session');

        // get the site's session config
        if (! \is_array($session) || ! isset($session['signature_key'])) {
            throw new \RuntimeException(\sprintf(
                "Session configuration signature key not found"
            ));
        }

        // prep the auth cookie
        $cookie = \array_merge(self::$cookieDefaults, $session['cookie']);
        $sameSite = match (\strtolower($cookie['same_site'])) {
            'strict' => SameSite::strict(),
            'none' => SameSite::none(),
            'lax' => SameSite::lax(),
            default => SameSite::lax(),
        };
        $authCookie = SetCookie::create($cookie['name'])
            ->withSecure($cookie['secure'] ?? true)
            ->withHttpOnly($cookie['http_only'] ?? true)
            ->withSameSite($sameSite)
            ->withPath($cookie['path'] ?? '/');

        // prep the JWT config
        $clock = SystemClock::fromUTC();
        $jwtConfig = JwtConfig::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($session['signature_key']));
        $constraints = [
            new IssuedBy('marshal'),
            new StrictValidAt($clock),
            new SignedWith($jwtConfig->signer(), $jwtConfig->verificationKey()),
        ];

        // prep the Storageless session config
        $config = Configuration::fromJwtConfiguration($jwtConfig->withValidationConstraints(...$constraints));
        return new SessionMiddleware($config
            ->withClock($clock)
            ->withCookie($authCookie)
            ->withClientFingerprintConfiguration(ClientFingerprintConfiguration::forIpAndUserAgent())
        );
    }
}
