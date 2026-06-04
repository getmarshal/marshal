<?php

declare(strict_types= 1);

namespace Marshal\Scheduler\Transport;

use Marshal\Database\Query\Create;
use Marshal\Database\Query\Select;
use Marshal\Scheduler\ScheduledTask;
use Marshal\Scheduler\TransportInterface;
use Marshal\Utils\Logger\LoggerManager;

final class DatabaseTransport implements TransportInterface
{
    public function getDue(): array|\Traversable
    {
        return Select::from(ScheduledTask::class)
            ->fetchAllAssociative();
    }

    public function schedule(ScheduledTask $task): bool
    {
        // @todo apply checks whether task should be saved

        try {
            Create::fromObject($task);
        } catch (\Throwable $e) {
            LoggerManager::get()->error($e->getMessage());
            return false;
        }

        return true;
    }
}
