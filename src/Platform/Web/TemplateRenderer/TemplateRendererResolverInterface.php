<?php

declare(strict_types=1);

namespace Marshal\Platform\Web\TemplateRenderer;

interface TemplateRendererResolverInterface
{
    public function resolve(string $template): TemplateRendererInterface;
}
