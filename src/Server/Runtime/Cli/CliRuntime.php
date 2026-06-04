<?php

declare(strict_types=1);

namespace Marshal\Server\Runtime\Cli;

use Marshal\Server\Runtime\RuntimeInterface;

class CliRuntime implements RuntimeInterface
{
    public function __construct(private Application $application)
    {
    }

    public function run(): void
    {
        $this->application->run();
    }
}
