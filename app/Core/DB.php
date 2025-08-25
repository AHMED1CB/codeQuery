<?php

namespace App\Core;
use App\Core\QueryBuilder;

class DB{

    public static function table($table){
        return new QueryBuilder($table);
    }

}