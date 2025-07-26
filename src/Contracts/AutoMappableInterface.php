<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

/**
 * Allows automatic construction from an array, using static rules.
 */
interface AutoMappableInterface
{
    /**
     * @param array<string, scalar|null> $data
     * @return list<mixed>
     */
    public static function mapFromArray(array $data): array;
}
