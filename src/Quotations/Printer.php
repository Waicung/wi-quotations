<?php

declare(strict_types=1);

namespace App\Quotations;

use Dompdf\Dompdf;
use App\Quotations\Templates\Loader;

class Printer
{

    public static function toPdf($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    public static function print($template, $data)
    {
        $html = Loader::load($template, $data);
        return self::toPdf($html);
    }
}
