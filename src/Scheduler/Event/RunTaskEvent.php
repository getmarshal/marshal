<?php

declare(strict_types=1);

namespace Marshal\Scheduler\Event;

final class RunTaskEvent
{
    use TaskEventTrait;

    public function __construct(readonly string $name, readonly array $params)
    {
        $this->name = $name;
        $this->params = $params;
    }
}
