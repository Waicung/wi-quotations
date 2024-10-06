<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;

function convertHtmlToPdf($html) {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    return $dompdf->output();
}