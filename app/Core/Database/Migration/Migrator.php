<?php

namespace App\Core\Database\Migration;

class Migrator
{
    protected MigrationRepository $repository;

    protected MigrationLoader $loader;

    public function __construct(
        MigrationRepository $repository,
        MigrationLoader $loader
    ) {
        $this->repository = $repository;
        $this->loader = $loader;
    }

    /**
     * Run all pending migrations.
     */
    public function migrate(): void
    {
        $this->loader->load();

        $executed = $this->repository->getRan();

        $pending = $this->loader->getPendingMigrations($executed);

        if (empty($pending)) {

            echo "No pending migrations.\n";

            return;
        }

        $batch = $this->repository->getNextBatchNumber();

        foreach ($pending as $migration) {

            $this->runMigration($migration, $batch);
        }
    }

    /**
     * Execute migration.
     */
    protected function runMigration(string $migration, int $batch): void 
    {

        $className = $this->resolveClassName($migration);

        if ($this->repository->hasRun($migration)) {
                return;
            }

        if (!class_exists($className)) {

            throw new \RuntimeException("Migration class {$className} not found.");
        }

        $instance = new $className();

        $instance->up();

        $this->repository->log(
            $migration,
            $batch
        );

        echo "Migrated: {$migration}\n";
    }

    /**
     * Rollback latest batch.
     */
    public function rollback(): void
    {
        $this->loader->load();

        $migrations =
            $this->repository
                ->getLastBatch();

        if (empty($migrations)) {

            echo "Nothing to rollback.\n";

            return;
        }

        foreach ($migrations as $migration) {

            $this->rollbackMigration(
                $migration['migration']
            );
        }
    }

    /**
     * Rollback one migration.
     */
    protected function rollbackMigration(
        string $migration
    ): void {

        $className =
            $this->resolveClassName(
                $migration
            );

        if (!class_exists($className)) {

            throw new \RuntimeException(
                "Migration class {$className} not found."
            );
        }

        $instance =
            new $className();

        $instance->down();

        $this->repository->delete(
            $migration
        );

        echo "Rolled Back: {$migration}\n";
    }

    /**
     * Convert filename
     * to class name.
     */
    protected function resolveClassName(
        string $migration
    ): string {

        $parts = explode(
            '_',
            $migration
        );

        $parts = array_slice(
            $parts,
            4
        );

        $className =
            implode(
                '_',
                $parts
            );

        return str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $className
                )
            )
        );
    }





    public function rollbackSteps(int $step): void 
    {

        $this->loader->load();

        $migrations = $this->repository->getLastMigrations($step);

        if (empty($migrations)) {

            echo "Nothing to rollback.". PHP_EOL;

            return;
        }

        foreach ($migrations as $migration) {

            $this->rollbackMigration($migration['migration']);
        }
    }


    public function reset(): void
    {
        $this->loader->load();

        $migrations = $this->repository->getAllMigrations();

        if (empty($migrations)) {

            echo "Nothing to reset.". PHP_EOL;

            return;
        }

        foreach ($migrations as $migration) {

            $this->rollbackMigration($migration['migration']);
        }
    }





}