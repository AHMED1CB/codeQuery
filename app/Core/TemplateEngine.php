<?php
namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use \Twig\TwigFunction;
class TemplateEngine
{
    private ?Environment $twig = null;

    private function initializeTwig(): void
    {
        if ($this->twig === null) {
            $loader = new FilesystemLoader(dirname(__FILE__, 2) . '\\Views');
            $this->twig = new Environment($loader, [
                'cache' => '../cache',
                'debug' => true,
            ]);

            $this->twig->addFunction(new TwigFunction('asset', function ($path) {
                return "/assets/{$path}";
            }));

            $this->twig->addFunction(new TwigFunction('lib', function ($path) {
                return "/lib/{$path}";
            }));

            $this->twig->addFunction(new TwigFunction('storage', function ($path) {
                return "/storage/{$path}";
            }));

            $this->twig->addFunction(new TwigFunction('count', function ($data){
                return  count($data);
            }));

        }
    }

    public function render(string $template, array $data = []): string
    {
        $this->initializeTwig();
        $template = str_replace('.', '\\', $template) . '.php.twig';
        return $this->twig->render($template, $data);
    }

    public function display(string $template, array $data = []): void
    {
        $this->initializeTwig();
        $template = str_replace('.', '\\', $template) . '.php.twig';

        $this->twig->display($template, $data);
    }
}