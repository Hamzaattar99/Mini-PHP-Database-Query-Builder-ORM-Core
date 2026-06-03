<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Conncetion;

class MigrationManager
{
    protected MigrationRepository $repository;

    protected MigrationLoader $loader;

    protected Migrator $migrator;

    public function __construct()
    {
        $this->repository =
            new MigrationRepository();

        $this->loader =
            new MigrationLoader();

        $this->migrator =
            new Migrator(
                $this->repository,
                $this->loader
            );
    }

    /**
     * Run pending migrations.
     */
    public function migrate(): void
    {
        $this->migrator->migrate();
    }

    /**
     * Rollback latest batch.
     */
    public function rollback(): void
    {
        $this->migrator->rollback();
    }

    /**
     * Executed migrations.
     */
    public function getExecuted(): array
    {
        return $this->repository->getRan();
    }

    /**
     * Pending migrations.
     */
    public function getPending(): array
    {
        return $this->loader
            ->getPendingMigrations(
                $this->repository->getRan()
            );
    }

    /**
     * Migration status.
     */
    public function status(): array
        {
            return [
                'executed' => $this->getExecuted(),
                'pending' => $this->getPending(),
            ];
        }

        public function hasPending(): bool
        {
            return !empty($this->getPending());
        }


        public function getStatus(): array
            {
                $executed =
                    $this->repository->getRan();

                $all =
                    $this->loader->getMigrationNames();

                $status = [];

                foreach ($all as $migration) {

                    $status[] = [

                        'migration' => $migration,

                        'ran' => in_array(
                            $migration,
                            $executed
                        )
                    ];
                }

                return $status;
            }



    public function rollbackSteps(int $step): void 
    {

        $this->migrator->rollbackSteps($step);
    }

    public function reset(): void
    {
        $this->migrator->reset();
    }

    public function refresh(): void
    {
        $this->reset();

        $this->migrator->migrate();
    }

    public function fresh(): void
    {
        $pdo = Conncetion::connect();

        // 1. Disable FK checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // 2. Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll();

        $protectedTables = ['migrations'];



        foreach ($tables as $table) {

            $tableName = array_values($table)[0];

            if (in_array($tableName, $protectedTables)) {
                continue;
                }

            $pdo->exec("DROP TABLE IF EXISTS {$tableName}");
        }

        // 3. Enable FK checks again
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // 4. Run migrations again
        $this->migrator->migrate();
    }


}