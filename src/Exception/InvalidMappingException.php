<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exceptions;

use InvalidArgumentException;
use Maduser\Argon\DTO\Contracts\DTOException;

/**
 * Thrown when DTO mapping fails due to invalid or incomplete input.
 */
final class InvalidMappingException extends InvalidArgumentException implements DTOException
{
    public static function missingField(string $field, string $class): self
    {
        return new self(sprintf(
            'Field "%s" is missing from input when mapping %s.',
            $field,
            $class
        ));
    }

    public static function invalidFieldType(string $field, string $expectedType, string $actualType, string $class): self
    {
        return new self(sprintf(
            'Invalid type for field "%s" in %s: expected %s, got %s.',
            $field,
            $class,
            $expectedType,
            $actualType
        ));
    }

    public static function parameterCountMismatch(int $expected, int $actual, string $class): self
    {
        return new self(sprintf(
            'Parameter count mismatch in %s: expected %d, got %d.',
            $class,
            $expected,
            $actual
        ));
    }
}
