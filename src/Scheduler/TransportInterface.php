<?php

declare(strict_types= 1);

namespace Marshal\Scheduler;

interface TransportInterface
{
    public function getDue(): array|\Traversable;
    public function schedule(ScheduledTask $task): bool;
}
