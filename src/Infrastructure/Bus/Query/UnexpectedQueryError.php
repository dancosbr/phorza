<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Query;

use Phorza\Infrastructure\Exception\BaseException;

final class UnexpectedQueryError extends BaseException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Unexpected query bus error';
}
