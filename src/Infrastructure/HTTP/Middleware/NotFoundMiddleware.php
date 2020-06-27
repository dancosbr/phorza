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
