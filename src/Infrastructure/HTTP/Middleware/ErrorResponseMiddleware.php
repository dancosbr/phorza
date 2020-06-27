<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\HTTP\Middleware;

use Phalcon\Mvc\Micro;
use Throwable;

class ErrorResponseMiddleware
{
    private Micro $app;

    const CATCHABLE_EXCEPTIONS = [
        \Phorza\Infrastructure\Exception\BaseException::class,
        \Phorza\Domain\Error\CreateException::class,
        \Phorza\Domain\Error\UpdateException::class,
        \Phorza\Domain\Error\DeleteException::class,
    ];

    public function __construct(Micro $app)
    {
        $this->app = $app;
    }

    /**
     * Calls the middleware
     *
     * @param Throwable $e
     *
     * @returns bool
     */
    public function __invoke(Throwable $e): bool
    {
        if (!in_array(get_class($e), self::CATCHABLE_EXCEPTIONS) && !in_array(get_parent_class($e), self::CATCHABLE_EXCEPTIONS)) {
            throw $e;
        }

        $errorCode = $e->getCode();
        if ($errorCode === 0) {
            $errorCode = 500;
        }

        $data = [
            'code' => $errorCode,
            'message' => $e->getMessage(),
        ];
        if (property_exists($e, 'errors')) {
            $data['errors'] = $e->errors;
        }

        $httpStatusCode = 500;
        if ($errorCode >= 400 && $errorCode < 600) {
            $httpStatusCode = $errorCode;
        }

        $this->app->response->setStatusCode($httpStatusCode)->setHeader('Content-Type', 'application/json')->sendHeaders();
        $this->app->response->setContent(json_encode($data))->send();

        return false;
    }
}
