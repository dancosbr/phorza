<?php

declare(strict_types=1);

namespace Phorza\Application\CountByCriteria;

use Phorza\Domain\Bus\Query\Query;

class CountByCriteriaQuery implements Query
{
    private array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
