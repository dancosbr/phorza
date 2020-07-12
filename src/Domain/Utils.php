<?php

declare(strict_types=1);

namespace Phorza\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Phorza\Domain\Error\JsonException;

final class Utils
{
    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    public static function stringToDate(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }

    public static function jsonEncode(array $values): string
    {
        $string = json_encode($values);

        if (JSON_ERROR_NONE !== json_last_error() || false === $string) {
            throw new JsonException('Unable to encode content into JSON: ' . json_last_error());
        }

        return $string;
    }

    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $data;
    }

    public static function toSnakeCase(string $text): string
    {
        if (ctype_lower($text)) {
            return $text;
        }

        $text = preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text);
        if (null === $text) {
            $text = '';
        }

        return strtolower($text);
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }
}
