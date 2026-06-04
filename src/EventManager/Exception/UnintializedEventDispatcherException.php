<?php

declare(strict_types=1);

namespace Marshal\EventManager\Exception;

class UnintializedEventDispatcherException extends \RuntimeException
{
    public function __construct(private string $message)
    {
        parent::__construct($message);
    }
}
