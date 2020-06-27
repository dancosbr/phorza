<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Query;

use Phorza\Domain\Bus\Query\Query;
use RuntimeException;

final class QueryNotRegisteredError extends RuntimeException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Query bus error';

    public static function withQuery(Query $query): self
    {
        return new self(sprintf('The query %s has not a query handler associated', get_class($query)));
    }
}
