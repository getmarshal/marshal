<?php

declare(strict_types=1);

namespace Marshal\Search\Handler;

use Psr\Container\ContainerInterface;

final class SearchHandlerFactory
{
    public function __invoke(ContainerInterface $container): SearchHandler
    {
        return new SearchHandler();
    }
}
