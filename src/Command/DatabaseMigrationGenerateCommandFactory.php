<?php

declare(strict_types=1);

namespace Marshal\Application\Command;

use Psr\Container\ContainerInterface;

final class DatabaseMigrationGenerateCommandFactory
{
    public function __invoke(ContainerInterface $container): DatabaseMigrationGenerateCommand
    {
        return new DatabaseMigrationGenerateCommand();
    }
}
