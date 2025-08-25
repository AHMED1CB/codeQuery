<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Error\LoaderError;


class TemplateEngine{
       private Environment $twig;

    public function __construct(){
    
        $loader = new FilesystemLoader(dirname(__DIR__) ."/Views/");


        $this->twig = new Environment($loader , [
            'auto_reload' => true ,
        ]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        

        $this->addTemplateFunctions();


    }

    private function addTemplateFunctions(){

        $asset = new \Twig\TwigFunction('asset' , function ($path){
            return '/assets/' . $path;
        });

        $lib = new \Twig\TwigFunction('lib' , function ($path){
            return  '/lib/' . $path;
        });

        $this->twig->addFunction($asset);
        $this->twig->addFunction($lib);



    }


       
    public function render($template, $data = []) {
        $template = str_replace('.','/', $template);
        $template = $template . '.php.twig';

        try{
            return $this->twig->render($template, $data);
        }catch(LoaderError $e){
            return "Error : " . $e->getMessage();
        }
         
    }


    public function display($template, $data = []) {

         echo( $this->render($template, $data));

    }
    
    

}