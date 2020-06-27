<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Query;

use Phorza\Domain\Bus\Query\Query;
use Phorza\Domain\Bus\Query\QueryBus;
use Phorza\Domain\Bus\Query\Response;
use Prooph\ServiceBus\Plugin\Router\QueryRouter;
use Prooph\ServiceBus\QueryBus as ProophQueryBus;

final class InMemoryProophQueryBus implements QueryBus
{
    private ProophQueryBus $bus;

    public function __construct(QueryRouter $queryRouter)
    {
        $this->bus = new ProophQueryBus();
        $queryRouter->attachToMessageBus($this->bus);
    }

    public function ask(Query $query): ?Response
    {
        try {
            $response = $error = null;
            $this->bus->dispatch($query)->then(
                function ($result) use (&$response) {
                    $response = $result;
                },
                function ($err) use (&$error) {
                    $error = $err;
                }
            );
            if ($response) {
                return $response;
            } elseif ($error) {
                throw $error;
            } else {
                throw new \RuntimeException('Unknow response');
            }
        } catch (\Prooph\ServiceBus\Exception\MessageDispatchException $exception) {
            if ($exception->getPrevious()) {
                if ($exception->getPrevious() instanceof \Exception && strpos($exception->getPrevious()->getMessage(), 'QueryBus was not able to identify a Finder for query') === 0) {
                    throw QueryNotRegisteredError::withQuery($query);
                } else if ($exception->getPrevious() instanceof \Exception) {
                    throw $exception->getPrevious();
                }
            }
            throw $exception;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
