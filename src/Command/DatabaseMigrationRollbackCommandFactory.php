<?php

declare(strict_types=1);

namespace Marshal\Application\Command;

use Psr\Container\ContainerInterface;

final class DatabaseMigrationRollbackCommandFactory
{
    public function __invoke(ContainerInterface $container): DatabaseMigrationRollbackCommand
    {
        return new DatabaseMigrationRollbackCommand;
    }
}
