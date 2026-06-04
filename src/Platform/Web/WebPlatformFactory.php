<?php

declare(strict_types= 1);

namespace Marshal\Platform\Web;

use Marshal\Platform\Web\TemplateRenderer\TemplateRendererResolverInterface;
use Psr\Container\ContainerInterface;

final class WebPlatformFactory
{
    public function __invoke(ContainerInterface $container): WebPlatform
    {
        $templateRendererResolver = $container->get(TemplateRendererResolverInterface::class);
        return new WebPlatform($templateRendererResolver);
    }
}
