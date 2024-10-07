<?php

namespace App\Quotations\Templates;

class Loader
{
    public static function load($template)
    {
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . $template . '.php';

        return file_get_contents($templatePath);
        if (file_exists($templatePath)) {
            return file_get_contents($templatePath);
        } else {
            return '<html><body><h1>Template not found!</h1></body></html>';
        }
    }
}
