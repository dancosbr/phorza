<?php

declare(strict_types=1);

namespace Phorza\Domain\ValueObject;

use Phorza\Domain\Utils;
use ReflectionClass;

use function in_array;

abstract class Enum
{
    protected static array $cache = [];
    protected string $value;

    public function __construct(string $value)
    {
        $this->ensureIsBetweenAcceptedValues($value);

        $this->value = $value;
    }

    abstract protected function throwExceptionForInvalidValue(string $value): void;

    public static function __callStatic(string $name, array $args): Enum
    {
        return new static(self::values()[$name]);
    }

    public static function fromString(string $value): Enum
    {
        return new static($value);
    }

    public static function values(): array
    {
        $class = static::class;

        if (!isset(self::$cache[$class])) {
            $reflected = new ReflectionClass($class);
            self::$cache[$class] = $reflected->getConstants();
            uksort(self::$cache[$class], self::keysFormatter());
        }

        return self::$cache[$class];
    }

    public static function randomValue(): string
    {
        return self::values()[array_rand(self::values())];
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Enum $other): bool
    {
        return $other == $this;
    }

    private function ensureIsBetweenAcceptedValues(string $value): void
    {
        if (!in_array($value, static::values(), true)) {
            $this->throwExceptionForInvalidValue($value);
        }
    }

    public static function random(): self
    {
        return new static(self::randomValue());
    }

    private static function keysFormatter(): callable
    {
        return static fn ($unused, string $key): string => Utils::toCamelCase(strtolower($key));
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
