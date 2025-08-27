<?php
use App\Core\Router;
use App\Controllers\RoutesController;
use App\Controllers\QuestionsController;
use App\Controllers\AuthController;

use App\Core\Response;
use App\Core\TemplateEngine;

// App Routes (Will Change Controllers Soon)

$templateEngine = new TemplateEngine();

Router::new('/' , "GET" , [RoutesController::class , 'mainPage']);


// Questions
Router::new('/questions' , "GET" , [RoutesController::class , 'questionsPage']);
Router::new('/ask' , "GET" , [RoutesController::class , 'askPage']);

Router::new('/questions/:question' , "GET" , [QuestionsController::class , 'showQuestion']);


// Tags
Router::new('/tags' , "GET" , [RoutesController::class , 'tagsPage']);

// Users
Router::new('/users' , "GET" , [RoutesController::class , 'usersPage']);

Router::new('/users/:user' , "GET" , [RoutesController::class , 'userProfile']);


// Auth

Router::new('/auth/register' , "GET" , [RoutesController::class , 'registerPage']);
Router::new('/auth/login' , "GET" , [RoutesController::class , 'loginPage']);

Router::new('/auth/register' , "POST" , [AuthController::class , 'RegisterUser']);
Router::new('/auth/login' , "POST" , [AuthController::class , 'LoginUser']);




Router::$_on404 = function() use ($templateEngine) {
    Response::status(404);
    $templateEngine->display('404');
    
    
};
