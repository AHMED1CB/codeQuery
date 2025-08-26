<?php

namespace App\Core;
use \App\Core\Response;


class Router
{

    static $routes = [];

    public static $_on404 = null ;



    public static function new(string $path, string $method, $event): void
    {

    
        $route = [
            'method' => $method,
            'endpoint' => $path,
            'callback' => $event,
        ];

        self::$routes[] = $route;

         
    }



    public static function _run()
    {


        $currentEndpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentEndpoint = trim($currentEndpoint ,'/');
        $currentEndpoint = str_starts_with($currentEndpoint, '/') ? $currentEndpoint : "/" . $currentEndpoint;

        $currentMethod = $_SERVER['REQUEST_METHOD'];

        
        
        foreach (self::$routes as $route) {
            if ($route['method'] != $currentMethod) continue;
            
            
            $pattern = preg_replace("/\:([a-zA-Z0-9_]+)/", "([^/]+)", $route['endpoint']);
            $pattern = "@^" . $pattern . "$@";

            if (preg_match($pattern, $currentEndpoint , $matches)) {

                array_shift($matches);
                $matches = array_map('urldecode', $matches);

                if(is_array($route['callback'])){
                    [$class, $methodName] = $route['callback'];
                    $controller = new $class();

                    return call_user_func_array([ $controller , $methodName], $matches);
                }

                    return call_user_func_array($route['callback'], $matches);
            }
            
            
            
            
            
        }
        
        if (is_callable(self::$_on404)){
            call_user_func(self::$_on404);
        }else{
            Response::status(404);
            echo ("404 Not Found");
            return ;
        }


    }




}
