<?php
use App\Core\Router;
use App\Controllers\RoutesController;
use App\Core\Response;
use App\Core\TemplateEngine;

// App Routes

$templateEngine = new TemplateEngine();

Router::new('/' , "GET" , [RoutesController::class , 'mainPage']);


Router::_on404(function () use($templateEngine){
    
    Response::status(404);
    $templateEngine->display('404');

});
