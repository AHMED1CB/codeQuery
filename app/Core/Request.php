<?php

namespace App\Core;

class Request{
    static public function input(string $name = '' , bool $escape=True){

        $phpinput = json_decode(file_get_contents('php://input') , true) ?? [];
        $_rqst = array_merge($phpinput , $_REQUEST);

        if ($escape){
            return htmlspecialchars($_rqst[$name] ?? '');
        }else{
            return $_rqst[$name] ?? '';
        }

    }

    static public function group(){


        $group = [];
        $names = func_get_args();

        foreach($names as $name){

            $group[$name] = self::input($name);

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