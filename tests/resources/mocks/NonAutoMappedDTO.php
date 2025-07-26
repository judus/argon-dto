<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Maduser\Argon\DTO\AbstractDTO;

final readonly class NonAutoMappedDTO extends AbstractDTO
{
    public function __construct(public int $id)
    {
    }
}
