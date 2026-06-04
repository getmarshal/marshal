<?php

namespace Marshal\Scheduler;

use Marshal\Database\Schema\Content;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            "depdendencies" => $this->getDependencies(),
            "schema" => $this->getSchemaConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            "aliases" => [
                TransportInterface::class                     => Transport\DatabaseTransport::class,
            ],
            "factories" => [
                Transport\DatabaseTransport::class            => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            ],
        ];
    }

    private function getRoutesConfig(): array
    {
        return [
            "paths" => [
                "/tasks" => [
                    "methods" => ["GET"],
                    "middleware" => Handler\TasksDashboardHandler::class,
                    "name" => Handler\TasksDashboardHandler::DASHBOARD_HANDLER,
                    "options" => [
                        "template" => "marshal::tasks-dashboard",
                    ],
                ],
            ],
        ];
    }

    private function getSchemaConfig(): array
    {
        return [
            "properties" => [
                ScheduledTask::EVENT_NAME => [
                    "label" => "Event Name",
                    "description" => "Event name",
                    "name" => "event_name",
                    "type" => "string",
                    "length" => 255,
                ],
                ScheduledTask::EVENT_PARAMS => [
                    "label" => "Event Params",
                    "description" => "Event params",
                    "name" => "event_params",
                    "type" => "json",
                ],
                ScheduledTask::EVENT_STATUS => [
                    "label" => "Flag",
                    "description" => "Flag property",
                    "name" => "event_status",
                    "type" => "string",
                    "length" => 30,
                    "filters" => [
                        \Laminas\Filter\StringToLower::class => [],
                    ],
                    "validators" => [
                        \Laminas\Validator\StringLength::class => [
                            "min" => 1,
                            "max" => 30
                        ],
                    ],
                ],
                ScheduledTask::TIMEOUT => [
                    "label" => "Timeout",
                    "description" => "Task timeout in seconds",
                    "name" => "timeout",
                    "type" => "integer",
                    "filters" => [
                        \Laminas\Filter\ToInt::class => [],
                    ],
                ],
            ],
            "types" => [
                ScheduledTask::class => [
                    "database" => "marshal::scheduler",
                    "description" => "A scheduled task",
                    "name" => "Scheduled Task",
                    "properties" => [
                        Content::ID,
                        Content::TAG,
                        ScheduledTask::EVENT_NAME,
                        ScheduledTask::EVENT_PARAMS,
                        ScheduledTask::EVENT_STATUS,
                        ScheduledTask::TIMEOUT,
                        Content::CREATED_AT,
                        Content::UPDATED_AT,
                    ],
                    "table" => "scheduled_task",
                ],
            ],
        ];
    }
}
