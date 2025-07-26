<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exception;

use JsonException;
use RuntimeException;
use Throwable;

/**
 * Thrown when (de)serialization to/from JSON fails.
 */
final class DTOSerializationException extends RuntimeException implements DTOException
{
    public static function jsonEncodingFailed(string $class, JsonException $e): self
    {
        return new self("Failed to encode DTO [$class] to JSON.", 0, $e);
    }

    public static function jsonDecodingFailed(string $class, Throwable $e): self
    {
        return new self("Failed to decode JSON into DTO [$class].", 0, $e);
    }
}
