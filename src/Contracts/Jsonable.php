<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

interface Jsonable
{
    public function toJson(int $flags = 0): string;
}