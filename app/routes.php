<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Quotations\Printer;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $html = '<html><body><h1>Hello world!</h1>';
        $html .= '<form action="/generate-pdf" method="post">';
        $html .= '<button type="submit">生成并下载PDF</button>';
        $html .= '</form></body></html>';
        $response->getBody()->write($html);
        return $response;
    });

    $app->post('/generate-pdf', function (Request $request, Response $response) {
        // 获取发票 HTML

        $pdfContent = Printer::print('simple', [
            'invoiceNumber' => 'INV-123456',
            'invoiceDate' => '2020-01-01',
            'dueDate' => '2020-01-31',
            'total' => 1000,
            'tax' => 50,
            'grandTotal' => 1050,
            'items' => [
                ['description' => 'Item 1', 'quantity' => 2, 'price' => 250, 'total' => 500],
                ['description' => 'Item 2', 'quantity' => 1, 'price' => 500, 'total' => 500],
            ],
        ]);

        // 设置响应头，使浏览器将其识别为下载的 PDF 文件
        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="invoice.pdf"');

        $response->getBody()->write($pdfContent);
        return $response;
    });
};
