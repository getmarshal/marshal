<?php

declare(strict_types=1);

namespace Marshal\Scheduler;

use Psr\EventDispatcher\EventDispatcherInterface;
use Marshal\Database\Schema\Content;

final class ScheduledTask extends Content
{
    public const string EVENT_NAME = "scheduler::event-name";
    public const string EVENT_PARAMS = "scheduler::event-params";
    public const string EVENT_STATUS = "scheduler::event-status";
    public const string TIMEOUT = "scheduler::timeout";

    public function done(): void
    {
    }

    public function getEventName(): string
    {
        return $this->getPropertyValue(self::EVENT_NAME);
    }

    public function getEventParams(): array
    {
        return $this->getPropertyValue(self::EVENT_PARAMS);
    }

    public function getStatus(): string
    {
        return $this->getPropertyValue(self::EVENT_STATUS);
    }

    public function run(EventDispatcherInterface $eventDispatcher): void
    {
        $eventName = $this->getEventName();
        $event = new $eventName(...$this->getEventParams());
        $eventDispatcher->dispatch($event);
        $this->done();
    }
}
