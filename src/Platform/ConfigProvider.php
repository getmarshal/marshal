<?php

declare(strict_types=1);

namespace Marshal\Platform;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            "dependencies" => $this->getDependencies(),
            "events" => $this->getEventsConfig(),
            "twig" => $this->getTwigConfig(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            "factories" => [
                API\APIPlatform::class                                          => API\APIPlatformFactory::class,
                Middleware\DetectPlatformMiddleware::class                      => Middleware\DetectPlatformMiddlewareFactory::class,
                Middleware\NotFoundResponseMiddleware::class                    => Middleware\NotFoundResponseMiddlewareFactory::class,
                Web\WebPlatform::class                                          => Web\WebPlatformFactory::class,
                Web\TemplateRenderer\TemplateRendererResolverInterface::class   => Web\TemplateRenderer\TempateRendererResolverFactory::class,
                Web\TemplateRenderer\Twig\RuntimeLoader::class                  => Web\TemplateRenderer\Twig\RuntimeLoaderFactory::class,
                Web\TemplateRenderer\Twig\TwigTemplateRenderer::class           => Web\TemplateRenderer\Twig\TwigTemplateRendererFactory::class,
            ],
        ];
    }

    private function getEventsConfig(): array
    {
        return [
            'listeners' => [],
        ];
    }

    private function getTwigConfig(): array
    {
        return [
            "runtime_loaders" => [
                Web\TemplateRenderer\Twig\RuntimeLoader::class,
            ],
            "functions" => [
                [
                    "name" => "media",
                    "callable" => [Web\TemplateRenderer\Twig\UrlExtension::class, "media"],
                    "options" => [
                        "needs_context" => true,
                    ],
                ],
                [
                    "name" => "path",
                    "callable" => [Web\TemplateRenderer\Twig\UrlExtension::class, "path"],
                ],
                [
                    "name" => "static",
                    "callable" => [Web\TemplateRenderer\Twig\UrlExtension::class, "static"],
                    "options" => [
                        "needs_context" => true,
                        "needs_environment" => true,
                    ],
                ],
            ],
        ];
    }
}
