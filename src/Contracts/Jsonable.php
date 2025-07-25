<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

use JsonException;

interface Jsonable
{
    /**
     * Converts the object to a JSON string.
     *
     * @param int $flags JSON encoding flags.
     *
     * @throws JsonException If encoding fails.
     */
    public function toJson(int $flags = 0): string;
}
