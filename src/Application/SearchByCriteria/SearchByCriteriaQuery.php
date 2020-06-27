<?php

declare(strict_types=1);

namespace Phorza\Application\SearchByCriteria;

use Phorza\Domain\Bus\Query\Query;

class SearchByCriteriaQuery implements Query
{
    private array $filters;
    private array $orderBy;
    private ?int $limit;
    private ?int $offset;

    public function __construct(
        array $filters,
        array $orderBy = [],
        int $limit = null,
        int $offset = null
    ) {
        $this->filters = $filters;
        $this->orderBy = $orderBy;
        $this->limit   = $limit;
        $this->offset  = $offset;
    }

    public function filters(): array
    {
        return $this->filters;
    }

    public function orderBy(): ?array
    {
        return $this->orderBy;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}
