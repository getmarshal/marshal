<?php

declare(strict_types= 1);

namespace Marshal\Platform\API;

use Psr\Container\ContainerInterface;

final class APIPlatformFactory
{
    public function __invoke(ContainerInterface $container): APIPlatform
    {
        return new APIPlatform();
    }
}
