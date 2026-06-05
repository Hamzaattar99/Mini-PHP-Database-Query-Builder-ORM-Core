<?php

namespace App\Core\Database;

use App\Core\Database\Conncetion;

use App\Core\Database\Executor;



class Schema
{
    public static function create(string $table, callable $callback): string
    {

        if (self::tableExists($table)) {
            return "{$table} : IS EXSIST!";
        }

        $blueprint = new Blueprint($table);

        $tableObj = new Table($blueprint);

        $callback($tableObj);

        $sql = $blueprint->toSql();

        //$pdo = Conncetion::connect();


        // echo $sql;
        //  die();

        Executor::run($sql);

        return "TABLE {$table} CREATED SUCCESSFULLY!";

    }


    public static function drop(string $table):string
    {

        if (!self::tableExists($table)) {
            return "{$table} : IS NOT EXSIST!";
        }

        $sql = "DROP TABLE IF EXISTS {$table}";

        Executor::run($sql);

        return "{$table} : IS DROPPED SUCCESSFULLY!";
    }


    public static function table(string $table, callable $callback)
    {
        $blueprint = new Blueprint($table);

        $blueprint->setMode('alter');

        $callback(new Table($blueprint));

        $sql = $blueprint->toAlterSql();

        // echo $sql;
        // die();

        Executor::run($sql);
    }

    // ------- Table Exsist Check ---------------------------------------

    public static function tableExists(string $table): bool
    {
        $sql = "SHOW TABLES LIKE '{$table}'";
        
       $result =  Executor::run($sql);

        return $result ? 1 : 0;
    }
}



?>