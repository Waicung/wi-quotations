<?php

declare(strict_types=1);

use App\Models\Profile;
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
        return $view->render($response, 'index.twig', ['profiles' => Profile::all()]);
    });

    $app->get('/profiles', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'profiles.twig', [
            'profiles' => Profile::all()
        ]);
    });

    $app->post('/generate-quotation', function (Request $request, Response $response) {
        // 获取发票 HTML

        $pdfContent = Printer::print('simple', []);

        // 设置响应头，使浏览器将其识别为下载的 PDF 文件
        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="wi-quotation.pdf"');

        $response->getBody()->write($pdfContent);
        return $response;
    });

    $app->post('/generate-invoice', function (Request $request, Response $response) {
        // 获取发票 HTML

        $pdfContent = Printer::print('simple', []);

        // 设置响应头，使浏览器将其识别为下载的 PDF 文件
        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="wi-invoice.pdf"');

        $response->getBody()->write($pdfContent);
        return $response;
    });

    $app->post('/save-profile', function (Request $request, Response $response) {
        $data = $request->getParsedBody();

        // Validate and sanitize data as needed

        // Store data in the database
        Profile::create([
            'label' => $data['company_label'],
            'company_name' => $data['company_name'],
            'company_address' => $data['company_address'],
            'company_phone' => $data['company_phone'],
            'company_email' => $data['company_email']
        ]);

        // Redirect or return a response
        return $response->withStatus(302)->withHeader('Location', '/profiles');
    });
};
