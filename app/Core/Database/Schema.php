<?php

namespace App\Core\Database;

use App\Core\Database\Conncetion;

use PDO;

class Schema
{
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);

        $tableObj = new Table($blueprint);

        $callback($tableObj);

        $sql = $blueprint->toSql();

        $pdo = Conncetion::connect();

        $pdo->exec($sql);


    }


    public static function drop(string $table):void
    {
        $pdo = Conncetion::connect();

        $pdo->exec("DROP TABLE IF EXISTS {$table}");
    }


    public static function table(string $table, callable $callback)
    {
        $blueprint = new Blueprint($table);

        $blueprint->setMode('alter');

        $callback(new Table($blueprint));

        $sql = $blueprint->toAlterSql();

        Conncetion::connect()->exec($sql);
    }
}



?>