<?php

declare(strict_types=1);

namespace Marshal\Search;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            "dependencies" => $this->getDependencies(),
            "navigation" => $this->getRoutesConfig(),
            "templates" => $this->getTemplates(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            "factories" => [
                Handler\SearchHandler::class    => Handler\SearchHandlerFactory::class,
            ],
        ];
    }

    private function getRoutesConfig(): array
    {
        return [
            "paths" => [
                "/search" => [
                    "methods" => ["GET"],
                    "middleware" => Handler\SearchHandler::class,
                    "name" => Handler\SearchHandler::ROUTE_NAME,
                    "options" => [
                        "template" => "marshal::search",
                    ],
                ],
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            "marshal::search" => [
                "filename" => "/main/app/search.twig.html",
                "includes" => ["main::layout"],
            ],
        ];
    }
}
