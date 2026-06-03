<?php

namespace App\Core\Database\Migration;

class MigrationLoader
{
    protected string $migrationPath;

    public function __construct(
        string $migrationPath = null
    ) {
        $this->migrationPath =
            $migrationPath
            ?? dirname(__DIR__, 4) . '/database/migrations';
    }

    /**
     * Get all migration files.
     */
    public function getFiles(): array
    {
        $files = glob(
            $this->migrationPath . '/*.php'
        );

        sort($files);

        return $files;
    }

    /**
     * Load migration files.
     */
    public function load(): void
    {
        foreach ($this->getFiles() as $file) {
            require_once $file;
        }
    }

    /**
     * Get migration names.
     */
    public function getMigrationNames(): array
    {
        $names = [];

        foreach ($this->getFiles() as $file) {
            $names[] = pathinfo(
                $file,
                PATHINFO_FILENAME
            );
        }

        return $names;
    }

    /**
     * Get pending migrations.
     */
    public function getPendingMigrations(
        array $executedMigrations
    ): array {

        $pending = [];

        foreach ($this->getMigrationNames() as $migration) {

            if (!in_array(
                $migration,
                $executedMigrations
            )) {
                $pending[] = $migration;
            }
        }

        return $pending;
    }
}