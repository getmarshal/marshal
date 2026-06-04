<?php

declare(strict_types=1);

namespace Marshal\Database\Schema;

final class ContentConfig
{
    public function __construct(private readonly array $config)
    {
    }

    public function getCollectionTemplate(): string
    {
        return $this->config['templates']['collection'];
    }

    public function getContentTemplate(): string
    {
        return $this->config['templates']['content'];
    }

    public function getDatabase(): string
    {
        return $this->config["database"];
    }

    public function getDescription(): string
    {
        return $this->config["description"];
    }

    public function getFilters(): array
    {
        return $this->config["filters"] ?? [];
    }

    public function getHandler(): ?string
    {
        return $this->config["handler"] ?? null;
    }

    public function getName(): string
    {
        return $this->config["name"];
    }

    public function getRoutePrefix(): string
    {
        return $this->config['routing']['route_prefix'] ?? '';
    }

    public function getTable(): string
    {
        return $this->config["table"];
    }

    public function getValidators(): array
    {
        return $this->config["validators"] ?? [];
    }

    public function hasCollectionTemplate(): bool
    {
        return isset($this->config['templates']['collection']);
    }

    public function hasContentTemplate(): bool
    {
        return isset($this->config['templates']['content']);
    }

    public function hasRoutePrefix(): bool
    {
        return isset($this->config['routing']['route_prefix']);
    }
}
