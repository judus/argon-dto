<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Exception;

use InvalidArgumentException;

/**
 * Thrown when mapping fails due to invalid input structure.
 */
final class DTOMappingException extends InvalidArgumentException implements DTOException
{
    public static function missingMap(string $class): self
    {
        return new self(sprintf(
            'Class "%s" is missing MAPPABLE_FIELDS.',
            $class,
        ));
    }
    public static function missingField(string $field, string $class): self
    {
        return new self(sprintf(
            'Field "%s" is missing from input when mapping %s.',
            $field,
            $class
        ));
    }
}
