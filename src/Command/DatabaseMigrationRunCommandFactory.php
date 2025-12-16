<?php

declare(strict_types=1);

namespace Marshal\Application\Command;

use Psr\Container\ContainerInterface;

final class DatabaseMigrationRunCommandFactory
{
    public function __invoke(ContainerInterface $container): DatabaseMigrationRunCommand
    {
        return new DatabaseMigrationRunCommand;
    }
}
