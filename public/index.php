<?php
require_once(dirname(__FILE__) ."/autoloader.php");
require_once(dirname(__FILE__) ."/../vendor/autoload.php");
require_once(dirname(__FILE__) . "/../routes/web.php");




\App\Core\Router::_run();


