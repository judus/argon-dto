<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exceptions;

use Maduser\Argon\DTO\Contracts\DTOException;
use RuntimeException;
use Throwable;

/**
 * Thrown when a DTO fails to serialize to JSON or array.
 */
final class SerializationException extends RuntimeException implements DTOException
{
    public static function jsonEncodingFailed(string $class, Throwable $previous): self
    {
        return new self(sprintf(
            'Failed to serialize %s to JSON: %s',
            $class,
            $previous->getMessage()
        ), 0, $previous);
    }

    public static function unsupportedType(string $field, string $type, string $class): self
    {
        return new self(sprintf(
            'Field "%s" in %s contains unsupported type for serialization: %s',
            $field,
            $class,
            $type
        ));
    }
}
