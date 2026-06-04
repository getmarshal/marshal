<?php

declare(strict_types=1);

namespace Marshal\EventManager;

interface EventDispatcherAwareInterface
{
    public function getEventDispatcher(): EventDispatcher;
    public function setEventDispatcher(EventDispatcher $eventDispatcher): void;
}
