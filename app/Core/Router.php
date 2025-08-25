<?php

namespace App\Core;



class Router
{

    static $on404 = null;
    static $routes = [];
    public static function new(string $path, string $method, $event): void
    {

    

        self::$routes[] = [
            'method' => $method,
            'endpoint' => $path,
            'callback' => $event,
        ];

         
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

              return call_user_func(self::$on404);

            
        
        }



    }


    public static function _on404(callable $callback) {
        self::$on404  = $callback;
    }


}
