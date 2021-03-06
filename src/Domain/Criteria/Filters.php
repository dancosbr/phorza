<?php

declare(strict_types=1);

namespace Phorza\Domain\Criteria;

use Phorza\Domain\Collection;

final class Filters extends Collection
{
    protected function type(): string
    {
        return Filter::class;
    }

    public static function fromValues(array $values): self
    {
        return new self(array_map(self::filterBuilder(), $values));
    }

    public function add(Filter $filter): self
    {
        return new self(array_merge($this->items(), [$filter]));
    }

    public function filters(): array
    {
        return $this->items();
    }

    public function serialize(): string
    {
        return array_reduce(
            $this->items(),
            static fn (string $accumulate, Filter $filter) => sprintf('%s^%s', $accumulate, $filter->serialize()),
            ''
        );
    }

    private static function filterBuilder(): callable
    {
        return fn (array $values) => Filter::fromValues($values);
    }
}
