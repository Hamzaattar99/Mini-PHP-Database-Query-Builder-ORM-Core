<?php

namespace App\Core\Database;

class DB
{
    public static function connection()
    {
        return Conncetion::connect();
    }

    public static function table(string $table)
    {
        return new QueryBuilder($table);
    }

    public static function beginTransaction(): void
    {
        self::connection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::connection()->commit();
    }

    public static function rollback(): void
    {
        self::connection()->rollBack();
    }

    public static function getQueryLog(bool $logsTypeIdentifier = false): array
    {
        if($logsTypeIdentifier)
            {
                return Executor::getLogs();
            }
            else
             {
                return QueryBuilder::getQueryLog();
             }
    
    }

    public static function clearQueryLog(bool $logsTypeIdentifier = false): void
    {
        if($logsTypeIdentifier)
            {
                Executor::clearLogs();
            }
        else
            {
                QueryBuilder::clearQueryLog();
            }
        
    }

   
}


?>