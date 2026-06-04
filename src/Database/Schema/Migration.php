<?php

declare(strict_types=1);

namespace Marshal\Database\Schema;

use Marshal\Database\Query\Create;
use Marshal\Database\Query\Select;
use Marshal\Database\Query\Update;

final class Migration extends Content
{
    public const string MIGRATION_ID = "database::migration-id";
    public const string MIGRATION_NAME = "database:migration-name";
    public const string MIGRATION_DATABASE = "database::migration-db";
    public const string MIGRATION_DIFF = "database::migration-diff";
    public const string MIGRATION_STATUS = "database::migration-status";
    public const string MIGRATION_TAG = "database::migration-tag";
    public const string MIGRATION_CREATEDAT = "database::migration-createdat";
    public const string MIGRATION_UPDATEDAT = "database::migration-updatedat";

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->getProperty(self::MIGRATION_CREATEDAT)->getValue();
    }

    public function getDatabase(): string
    {
        return $this->getProperty(self::MIGRATION_DATABASE)->getValue();
    }

    public function getDiff(): string
    {
        return $this->getProperty(self::MIGRATION_DIFF)->getValue();
    }

    public function getMigrationDiff(): SchemaDiff
    {
        $diff = \unserialize($this->getDiff());
        if (! $diff instanceof SchemaDiff) {
            throw new \RuntimeException(\sprintf(
                "Could not unserialize diff for migration %s",
                $this->getName()
                ));
        }

        return $diff;
    }

    public function getName(): ?string
    {
        return $this->getProperty(self::MIGRATION_NAME)->getValue();
    }

    public function getStatus(): ?bool
    {
        return $this->getProperty(self::MIGRATION_STATUS)->getValue();
    }

    public function getTag(): string
    {
        return $this->getProperty(self::MIGRATION_TAG)->getValue();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->getProperty(self::MIGRATION_UPDATEDAT)->getValue();
    }

    public function isEmpty(): bool
    {
        return null === $this->getAutoIncrement()->getValue() ? true : false;
    }

    public function updateMigrationOnCompletion(): self
    {
        Update::target($this)->withValues([
            self::MIGRATION_STATUS => true,
            self::MIGRATION_UPDATEDAT => new \DateTimeImmutable(timezone: new \DateTimeZone('UTC')),
        ])->execute();
    }

    public static function get(string $name): self
    {
        return Select::from(self::class)
            ->where(self::MIGRATION_NAME, $name)
            ->fetch();
    }

    public static function getMigrations(): array
    {
        return Select::from(self::class)
        ->orderBy(self::MIGRATION_CREATEDAT, 'DESC')
        ->fetchAllAssociative();
    }

    public static function nameExists(string $name): bool
    {
        $migration = self::get($name);
        return $migration->isEmpty() ? false : true;
    }

    public static function save(array $input): self
    {
        return Create::fromArray(self::class, $input)->execute();
    }
}
