<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

interface Arrayable
{
    /**
     * @return array<string, scalar|null>
     */
    public function toArray(): array;
}