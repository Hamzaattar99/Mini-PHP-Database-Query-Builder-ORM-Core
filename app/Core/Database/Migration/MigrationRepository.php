<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Conncetion;
use PDO;

class MigrationRepository
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conncetion::connect();

        $this->createRepositoryIfNotExists();
    }

    /**
     * Ensure migrations table exists.
     */
    protected function createRepositoryIfNotExists(): void
    {
        $query = "
            CREATE TABLE IF NOT EXISTS migrations (
                id BIGINT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->pdo->exec($query);
    }

    /**
     * Insert executed migration.
     */
    public function log(string $migration, int $batch): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO migrations
            (migration, batch)
            VALUES
            (:migration, :batch)
        ");

        return $stmt->execute([
            'migration' => $migration,
            'batch' => $batch,
        ]);
    }

    /**
     * Delete migration record.
     */
    public function delete(string $migration): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM migrations
            WHERE migration = :migration
        ");

        return $stmt->execute([
            'migration' => $migration
        ]);
    }

    /**
     * Get all executed migrations.
     */
    public function getRan(): array
    {
        $stmt = $this->pdo->query("
            SELECT migration
            FROM migrations
            ORDER BY id ASC
        ");

        return array_column(
            $stmt->fetchAll(),
            'migration'
        );
    }

    /**
     * Get latest batch number.
     */
    public function getLastBatchNumber(): int
    {
        $stmt = $this->pdo->query("
            SELECT MAX(batch) as batch
            FROM migrations
        ");

        $result = $stmt->fetch();

        return (int) ($result['batch'] ?? 0);
    }

    /**
     * Get next batch number.
     */
    public function getNextBatchNumber(): int
    {
        return $this->getLastBatchNumber() + 1;
    }

    /**
     * Get migrations from latest batch.
     */
    public function getLastBatch(): array
    {
        $batch = $this->getLastBatchNumber();

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM migrations
            WHERE batch = :batch
            ORDER BY id DESC
        ");

        $stmt->execute([
            'batch' => $batch
        ]);

        return $stmt->fetchAll();
    }


    public function getLastMigrations(int $step): array 
        {

            $stmt = $this->pdo->prepare("SELECT * FROM migrations ORDER BY id DESC LIMIT {$step}");

            $stmt->execute();

            return $stmt->fetchAll();
        }

    public function getAllMigrations(): array
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM migrations
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function hasRun(string $migration): bool
    {
        $pdo = $this->pdo;

        $stmt = $pdo->prepare("
            SELECT 1
            FROM migrations 
            WHERE migration = :migration
            LIMIT 1
        ");

        $stmt->execute([
            'migration' => $migration
        ]);

        return $stmt->fetch() !== false;
    }


}