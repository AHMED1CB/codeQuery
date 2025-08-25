<?php

namespace App\Core;

class Request{

    protected static $_request = $_REQUEST;
    static public function input(string $name = ''){

        return htmlspecialchars(self::$_request[$name]);    

    }

    static public function group(...$names ){


        $group = [];

        foreach($names as $name){

            $group[$name] = htmlspecialchars(self::input($name));

        }


    }

}