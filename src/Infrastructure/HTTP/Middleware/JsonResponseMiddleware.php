<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\HTTP\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class JsonResponseMiddleware implements MiddlewareInterface
{
    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @returns bool
     */
    public function call(Micro $app): bool
    {
        if (empty($app->response->getStatusCode())) {
            $app->response->setStatusCode(200);
        }

        $data = [];

        $response = $app->getReturnedValue();
        if (is_array($response)) {
            $data = $this->prepareContent($response, $data);
        }

        $app->response->setHeader('Content-Type', 'application/json');
        if (isset($data['code']) && $data['code'] >= 200 && $data['code'] < 300) {
            $app->response->setStatusCode($data['code']);
        }
        if (!isset($data['code']) || (isset($data['code']) && $data['code'] !== 204)) {
            $app->response->setContent(json_encode($data));
        }
        $app->response->send();

        return true;
    }

    private function prepareContent(array $response, array $data): array
    {
        if (isset($response['code'])) {
            $data['code'] = $response['code'];
            unset($response['code']);
        }
        if (isset($response['message'])) {
            $data['message'] = $response['message'];
            unset($response['message']);
        }
        if (isset($response['total'])) {
            $data['total'] = $response['total'];
            unset($response['total']);
        }
        if (isset($response['data'])) {
            $data['data'] = $response['data'];
            unset($response['data']);
        }
        if (count($response)) {
            $data['extras']  = $response;
        }

        return $data;
    }
}
