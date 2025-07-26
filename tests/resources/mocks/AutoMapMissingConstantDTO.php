<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Maduser\Argon\DTO\AbstractDTO;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Traits\AutoMap;

final readonly class AutoMapMissingConstantDTO extends AbstractDTO implements AutoMappableInterface
{
    use AutoMap;

    public function __construct(
        public int $id,
        public string $name
    ) {
    }
}
