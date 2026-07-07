<?php

return [
    "database" => [
        "marshal::migration" => [
            "label" => "Migration",
            "tag" => "migration",
            "driver" => "pdo_sqlite",
            "driverOptions" => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ],
            "path" => __DIR__ . "/../data/migrations.sqlite",
        ],
        "marshal::scheduler" => [
            "label" => "Scheduler",
            "system" => true,
            "tag" => "scheduler",
            "driver" => "pdo_sqlite",
            "driverOptions" => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ],
            "path" => __DIR__ . "/../data/scheduler.sqlite",
        ],
        "marshal::user" => [
            "label" => "User",
            "system" => true,
            "tag" => "user",
            "driver" => "pdo_sqlite",
            "driverOptions" => [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ],
            "path" => __DIR__ . "/../data/user.sqlite",
        ],
    ],
    "system" => [
        "directories" => [
            "assets" => __DIR__ . "/../public/static",
            "config" => __DIR__,
            "data" => __DIR__ . "/../data",
            "uploads" => __DIR__ . "/../public/uploads",
        ],
    ],
];
