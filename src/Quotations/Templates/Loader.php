<?php

namespace App\Quotations\Templates;

class Loader
{
    public static function load($template, $data)
    {
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . $template . '.twig';

        if (file_exists($templatePath)) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__);
            $twig = new \Twig\Environment($loader);

            // Log or print the data to confirm it is passed through
            error_log(print_r($data, true));

            return $twig->render($template . '.twig', $data);
        } else {
            return '<html><body><h1>Template not found!</h1></body></html>';
        }
    }
}
