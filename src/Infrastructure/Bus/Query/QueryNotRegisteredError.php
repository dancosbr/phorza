<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Query;

use Phorza\Domain\Bus\Query\Query;
use Phorza\Infrastructure\Exception\BaseException;

final class QueryNotRegisteredError extends BaseException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Query not registered on query bus';

    public static function withQuery(Query $query): self
    {
        return new self(sprintf('The query %s has not a query handler associated', get_class($query)));
    }
}
