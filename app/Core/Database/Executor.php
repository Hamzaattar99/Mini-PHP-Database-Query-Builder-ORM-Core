<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class Executor
{
    protected static array $logs = [];

    public static function run(string $sql, array $bindings = []): mixed
    {
        $pdo = Conncetion::connect();

        $start = microtime(true);

        try {
            $stmt = $pdo->prepare($sql);

            $stmt->execute($bindings);

            $result = $stmt->fetchAll();

            self::$logs[] = [
                'sql' => $sql,
                'bindings' => $bindings,
                'time' => microtime(true) - $start,
                'status' => 'success'
            ];

            return $result;

        } catch (PDOException $e) {

            self::$logs[] = [
                'sql' => $sql,
                'bindings' => $bindings,
                'time' => microtime(true) - $start,
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            throw $e;
        }
    }

    public static function getLogs(): array
    {
        return self::$logs;
    }

    public static function clearLogs(): void
    {
        self::$logs = [];
    }
}