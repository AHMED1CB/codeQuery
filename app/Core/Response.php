<?php

namespace App\Core;


class Response{

    static public function json(array $data , int $status = 200){
    
        self::status($status);
        return json_encode($data);
        
    }

    static public function display(array $data){
        echo self::json($data);
    }
    

    static public function status(int $code){
        http_response_code($code);
    }


      public function redirect(string $url) {
        header("Location: $url");
        exit;
    }


}