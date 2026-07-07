<?php

declare(strict_types=1);

namespace Marshal\Platform\Web\TemplateRenderer\Twig;

use Psr\Container\ContainerInterface;

final class TwigTemplateRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigTemplateRenderer
    {
        $twigEnvironmentOptions = [
            'debug' => $container->get('config')['debug'] ?? FALSE,
            'use_yield' => TRUE,
        ];

        $config = $container->get('config')['twig'] ?? [];
        $templatesConfig = $container->get('config')['templates'] ?? [];

        return new TwigTemplateRenderer($container, $config, $twigEnvironmentOptions, $templatesConfig);
    }
}
