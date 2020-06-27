<?php

declare(strict_types=1);

namespace Phorza\Application;

use Phorza\Domain\Bus\Query\Response;

final class CounterResponse implements Response
{
    private int $total;

    public function __construct(int $total)
    {
        $this->total = $total;
    }

    public function total(): int
    {
        return $this->total;
    }
}
