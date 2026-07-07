<?php

declare(strict_types=1);

namespace Marshal\Form;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            "dependencies" => $this->getDependencies(),
            "navigation" => $this->getRoutes(),
            "templates" => $this->getTemplates(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            "factories" => [
                Handler\IndexHandler::class         => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            "paths" => [
                "/forms" => [
                    "methods" => ["GET"],
                    "middleware" => Handler\IndexHandler::class,
                    "name" => Handler\IndexHandler::INDEX_PAGE,
                ],
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            Handler\IndexHandler::INDEX_PAGE => [
                "filename" => "/main/forms/index.twig.html",
                "includes" => ["main::layout"],
            ],
        ];
    }
}
