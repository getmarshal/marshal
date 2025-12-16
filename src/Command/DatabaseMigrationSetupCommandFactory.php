<?php

declare(strict_types=1);

namespace Marshal\Application\Command;

use Psr\Container\ContainerInterface;

final class DatabaseMigrationSetupCommandFactory
{
    public function __invoke(ContainerInterface $container): DatabaseMigrationSetupCommand
    {
        return new DatabaseMigrationSetupCommand;
    }
}
