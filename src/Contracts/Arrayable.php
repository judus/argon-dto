<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

interface Arrayable
{
    /** @return array<string, mixed> */
    public function toArray(): array;
}
