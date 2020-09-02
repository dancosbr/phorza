<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\HTTP\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

final class NotFoundMiddleware implements MiddlewareInterface
{
    public function beforeNotFound(Event $event, Micro $app): bool
    {
        $app->response->setHeader('Access-Control-Allow-Origin', '*');
        $app->response->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $app->response->setHeader('Access-Control-Allow-Headers', 'content-type');
        if ($app->request->isOptions()) {
            /** @var \Phalcon\Mvc\Router\Route $route */
            foreach ($app->getRouter()->getRoutes() as $route) {
                if ($route->getPattern() === '.*') {
                    continue;
                }
                @preg_match($route->getCompiledPattern(), $app->request->getURI(), $matches);
                if ($route->getCompiledPattern() === $app->request->getURI() || !empty($matches)) {
                    $app->response->setStatusCode(200)->setHeader('Content-Type', 'application/json')->sendHeaders();
                    $app->response->send();
                    return false;
                }
            }
        }

        $app->response->setStatusCode(404, 'Not Found')->setHeader('Content-Type', 'application/json')->sendHeaders();
        $app->response->setJsonContent([
            'code' => 404,
            'message' => 'Resource not found',
        ])->send();

        return false;
    }

    public function call(Micro $app): bool
    {
        return true;
    }
}
