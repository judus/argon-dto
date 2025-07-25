<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exception;

use RuntimeException;

/**
 * Thrown when a DTO is improperly configured or missing required behavior.
 */
final class DTOConfigurationException extends RuntimeException
{
    public static function missingAutoMapper(string $class): self
    {
        return new self(sprintf(
            '%s must implement AutoMappableInterface or override fromArray().',
            $class
        ));
    }
}