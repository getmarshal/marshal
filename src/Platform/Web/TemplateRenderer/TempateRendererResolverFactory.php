<?php

declare(strict_types=1);

namespace Marshal\Platform\Web\TemplateRenderer;

use Psr\Container\ContainerInterface;

final class TempateRendererResolverFactory
{
    public function __invoke(ContainerInterface $container): TemplateRendererResolver
    {
        $config = $container->get('config')['templates'] ?? [];
        return new TemplateRendererResolver($container, $config);
    }
}
