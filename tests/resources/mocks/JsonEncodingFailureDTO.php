<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Maduser\Argon\DTO\AbstractDTO;

final readonly class JsonEncodingFailureDTO extends AbstractDTO
{
    public function __construct()
    {
    }

    protected function serializeToArray(): array
    {
        return ['stream' => fopen('php://memory', 'r')];
    }
}
