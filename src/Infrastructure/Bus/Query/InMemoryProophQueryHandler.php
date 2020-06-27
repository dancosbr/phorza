<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Query;

use Phorza\Domain\Bus\Query\Query;
use Phorza\Domain\Bus\Query\QueryHandler;
use React\Promise\Deferred;

final class InMemoryProophQueryHandler implements QueryHandler
{
    private QueryHandler $handler;

    public function __construct(QueryHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Query $query, Deferred $deferred): void
    {
        try {
            $handler = $this->handler;
            $result = $handler($query);
            $deferred->resolve($result);
        } catch (\Exception $exception) {
            $deferred->reject($exception);
        }
    }
}
