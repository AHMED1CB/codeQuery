<?php

namespace App\Core;

class Request{
    static public function input(string $name = ''){

        $phpinput = json_decode(file_get_contents('php://input') , true) ?? [];
        $_rqst = $_REQUEST + $phpinput;

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

    static public function has(string $name ){
    
            return !empty(self::input($name));

    }


    static public function hasFile(string $name){
    
        return isset($_FILES[$name]);
    }


    static public function file(string $name){

        return $_FILES[$name] ?? [];
    }

}