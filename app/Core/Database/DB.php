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
}


?>