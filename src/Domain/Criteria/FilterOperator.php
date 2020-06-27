<?php

declare(strict_types=1);

namespace Phorza\Domain\Criteria;

use Phorza\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator like()
 */
final class FilterOperator extends Enum
{
    public const EQUAL        = '=';
    public const NOT_EQUAL    = '!=';
    public const GT           = '>';
    public const LT           = '<';
    public const LIKE         = 'LIKE';

    public static function equal(): self
    {
        return new self('=');
    }

    protected function throwExceptionForInvalidValue(string $value): void
    {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
