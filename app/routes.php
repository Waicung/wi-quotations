<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Quotations\Printer;
use Slim\Views\Twig;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'index.twig');
    });

    $app->post('/generate-pdf', function (Request $request, Response $response) {
        // 获取发票 HTML

        $pdfContent = Printer::print('simple', []);

        // 设置响应头，使浏览器将其识别为下载的 PDF 文件
        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="wi-quotations.pdf"');

        $response->getBody()->write($pdfContent);
        return $response;
    });
};
