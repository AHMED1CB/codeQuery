<?php

namespace App\Core;

class Request{
    static public function input(string $name = ''){

        $_rqst = $_REQUEST;

        return htmlspecialchars($_rqst[$name] ?? '');    

    }

    static public function group(){


        $group = [];
        $names = func_get_args();

        foreach($names as $name){

            $group[$name] = htmlspecialchars(self::input($name));

        }

        return $group;


    }

}