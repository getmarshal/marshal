<?php

namespace Marshal\EventManager\Exception;

final class ListenerNotFoundException extends \RuntimeException
{
    public function __construct(\Throwable $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }
}
