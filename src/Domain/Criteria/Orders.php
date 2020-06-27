<?php

declare(strict_types=1);

namespace Phorza\Domain\Criteria;

use Phorza\Domain\Collection;

final class Orders extends Collection
{
    protected function type(): string
    {
        return Order::class;
    }

    public function add(Order $order): self
    {
        return new self(array_merge($this->items(), [$order]));
    }

    public function orders(): array
    {
        return $this->items();
    }

    public function serialize(): string
    {
        return array_reduce(
            $this->items(),
            static fn (string $accumulate, Order $order) => sprintf('%s^%s', $accumulate, $order->serialize()),
            ''
        );
    }
}
