<?php

declare(strict_types=1);

namespace Marshal\EventManager;

trait EventDispatcherAwareTrait
{
    private EventDispatcher $eventDispatcher;

    public function getEventDispatcher(): EventDispatcher
    {
        if (! isset($this->eventDispatcher)) {
            throw new Exception\UnintializedEventDispatcherException(\sprintf(
                "%s not found. Use the %s to delegate the service",
                EventDispatcher::class,
                EventDispatcherDelegatorFactory::class
            ));
        }

        return $this->eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcher $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
