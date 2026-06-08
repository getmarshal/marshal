<?php

declare(strict_types=1);

namespace Marshal\Scheduler\Event;

trait TaskEventTrait
{
    private string $name;
    private array $params;

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
