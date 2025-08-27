<?php

namespace App\Core;

class Session{

    static public function set($name , $value){
        $_SESSION[$name] = $value;
    }

    static public function get($name){
        return $_SESSION[$name];
    }

    static public function destroy(){
        session_destroy();
    }

    static public function remove($name){
        unset($_SESSION[$name]);
    }

    static public function has($name){

        return isset($_SESSION[$name]);
    }



}