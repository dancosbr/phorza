<?php

declare(strict_types=1);

namespace Phorza\Domain\Criteria;

final class Criteria
{
    private Filters $filters;
    private Orders $orders;
    private ?int $offset;
    private ?int $limit;

    public function __construct(Filters $filters, ?Orders $orders = null, ?int $offset = null, ?int $limit = null)
    {
        $this->filters = $filters;
        $this->orders  = $orders instanceof Orders ? $orders : new Orders([]);
        $this->offset  = $offset;
        $this->limit   = $limit;
    }

    public function hasFilters(): bool
    {
        return $this->filters->count() > 0;
    }

    public function hasOrder(): bool
    {
        return $this->orders->count() > 0;
    }

    public function plainFilters(): array
    {
        return $this->filters->filters();
    }

    public function filters(): Filters
    {
        return $this->filters;
    }

    public function orders(): Orders
    {
        return $this->orders;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function serialize(): string
    {
        return sprintf(
            '%s~~%s~~%s~~%s',
            $this->filters->serialize(),
            $this->orders->serialize(),
            $this->offset,
            $this->limit
        );
    }
}
