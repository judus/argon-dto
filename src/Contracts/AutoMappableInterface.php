<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

/**
 * Optional interface to enable dynamic constructor mapping.
 */
interface AutoMappableInterface
{
    /**
     * Maps an associative array of input data to an ordered param array.
     *
     * @param array<string, mixed> $data
     * @return array<int, mixed>
     */
    public static function mapFromArray(array $data): array;
}
