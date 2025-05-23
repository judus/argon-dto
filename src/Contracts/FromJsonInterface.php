<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Contracts;

interface FromJsonInterface
{
    public static function fromJson(string $json): static;
}