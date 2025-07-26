<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Traits;

use InvalidArgumentException;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Exception\DTOMappingException;

trait AutoMap
{
    /**
     * @return list<mixed>
     */
    public static function mapFromArray(array $data): array
    {
        $fqcn = static::class;

        if (!defined("$fqcn::MAPPABLE_FIELDS")) {
            throw DTOMappingException::missingMap($fqcn);
        }

        /** @var list<string> $fields */
        $fields = constant("$fqcn::MAPPABLE_FIELDS");

        return array_map(
            static function (string $key) use ($data, $fqcn): mixed {
                if (!array_key_exists($key, $data)) {
                    throw DTOMappingException::missingField($key, $fqcn);
                }

                return $data[$key];
            },
            $fields
        );
    }
}
