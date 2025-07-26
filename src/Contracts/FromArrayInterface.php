<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

interface FromArrayInterface
{
    /**
     * @param array<string, scalar|null> $data
     */
    public static function fromArray(array $data): static;
}
