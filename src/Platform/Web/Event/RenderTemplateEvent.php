<?php

declare(strict_types=1);

namespace Marshal\Platform\Web\Event;

use Psr\Http\Message\ServerRequestInterface;

class RenderTemplateEvent
{
    private string $contents = "";

    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly array|\Traversable $data = [],
        private readonly array $options = []
    ) {
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getData(): array|\Traversable
    {
        return $this->data;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setContents(string $contents): static
    {
        $this->contents = $contents;
        return $this;
    }
}
