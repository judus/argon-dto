<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO\Traits;

use Maduser\Argon\DTO\Exceptions\InvalidMappingException;
use ReflectionClass;
use ReflectionParameter;

trait AutoMappable
{
    protected static ?array $mappables = null;

    public static function mapFromArray(array $data): array
    {
        $params = [];

        foreach (static::mappables() as $field) {
            if (!array_key_exists($field, $data)) {
                throw InvalidMappingException::missingField($field, static::class);
            }
            $params[] = $data[$field];
        }

        return $params;
    }

    protected static function mappables(): array
    {
        if (static::$mappables !== null) {
            return static::$mappables;
        }

        $ctor = (new ReflectionClass(static::class))->getConstructor();

        return array_map(
            static fn(ReflectionParameter $param) => $param->getName(),
            $ctor?->getParameters() ?? []
        );
    }
}
