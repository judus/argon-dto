<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

use JsonException;

interface FromJsonInterface
{
    /**
     * Creates a new instance from a JSON string.
     *
     * @param string $json Valid JSON string.
     *
     * @throws JsonException If decoding fails.
     */
    public static function fromJson(string $json): static;
}
