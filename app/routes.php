<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

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
        // 调用 HTML 到 PDF 的转换功能
        require_once __DIR__ . '/../html_to_pdf.php';
        $html = '<h1>这是一个示例PDF</h1><p>生成时间：' . date('Y-m-d H:i:s') . '</p>';
        $pdfContent = convertHtmlToPdf($html);

        // 设置响应头，使浏览器将其识别为下载的 PDF 文件
        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="generated.pdf"');

        $response->getBody()->write($pdfContent);
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
