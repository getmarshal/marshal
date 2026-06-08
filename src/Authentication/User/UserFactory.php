<?php

declare(strict_types=1);

namespace Marshal\Authentication\User;

use Marshal\Database\Hydrator\ItemInputHydrator;
use Marshal\Database\Schema\ContentManager;
use Psr\Container\ContainerInterface;

final class UserFactory
{
    public function __invoke(ContainerInterface $container): callable
    {
        $user = ContentManager::get(User::class);
        \assert($user instanceof UserInterface);

        $hydrator = new ItemInputHydrator();

        return static function (array $details) use ($user, $hydrator): UserInterface {
            $hydrator->hydrate($user, $details);
            return $user;
        };
    }
}
