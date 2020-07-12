<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\HTTP\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phorza\Infrastructure\Exception\InvalidRequestException;

class RequestMiddleware implements MiddlewareInterface
{
    const EXPECTED_CONTENT_TYPE = 'application/json';
    /**
     * @param Event $event
     * @param Micro $app
     *
     * @returns bool
     */
    public function beforeExecuteRoute(
        Event $event,
        Micro $app
    ): bool {
        if (!in_array($app->request->getMethod(), ['POST', 'PUT'])) {
            return true;
        }

        json_decode(
            $app
                ->request
                ->getRawBody()
        );
        if (JSON_ERROR_NONE !== json_last_error()) {
            $app->response->setStatusCode(400, 'Bad Request')->setHeader('Content-Type', self::EXPECTED_CONTENT_TYPE)->sendHeaders();
            $app->response->setJsonContent(['errors' => [['status' => 400, 'message' => 'Invalid content']]])->send();

            exit;
        }

        if ($app->request->getContentType() !== self::EXPECTED_CONTENT_TYPE) {
            $app->response->setStatusCode(400, 'Bad Request')->setHeader('Content-Type', self::EXPECTED_CONTENT_TYPE)->sendHeaders();
            $app->response->setJsonContent(['errors' => [['status' => 400, 'message' => 'Invalid content-type']]])->send();

            exit;
        }

        return true;
    }

    /**
     * @param Micro $app
     *
     * @returns bool
     */
    public function call(Micro $app): bool
    {
        return true;
    }
}
