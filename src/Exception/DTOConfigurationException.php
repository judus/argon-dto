<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exception;

use RuntimeException;

/**
 * Thrown when a DTO is misconfigured (e.g., missing required contracts).
 */
final class DTOConfigurationException extends RuntimeException implements DTOException
{
    public static function missingAutoMapper(string $class): self
    {
        return new self(sprintf(
            '%s must implement AutoMappableInterface or override fromArray().',
            $class
        ));
    }
}
