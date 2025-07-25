<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO;

use JsonException;
use JsonSerializable;
use Maduser\Argon\DTO\Contracts\DTOInterface;
use Maduser\Argon\DTO\Contracts\FromJsonInterface;
use Maduser\Argon\DTO\Contracts\Jsonable;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Exception\DTOConfigurationException;
use Maduser\Argon\DTO\Exception\SerializationException;

abstract readonly class AbstractDTO implements
    DTOInterface,
    FromJsonInterface,
    Jsonable,
    JsonSerializable
{
    final public static function fromArray(array $data): static
    {
        if (!is_subclass_of(static::class, AutoMappableInterface::class)) {
            throw DTOConfigurationException::missingAutoMapper(static::class);
        }

        /** @var class-string<AutoMappableInterface> $class */
        $class = static::class;
        $params = $class::mapFromArray($data);

        return new static(...$params);
    }

    /**
     * @throws JsonException
     */
    final public static function fromJson(string $json): static
    {
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        return static::fromArray($data);
    }

    final public function toArray(): array
    {
        return $this->serializeToArray();
    }

    /**
     * Override for custom serialization.
     *
     * @return array<string, mixed>
     */
    protected function serializeToArray(): array
    {
        return get_object_vars($this);
    }


    final public function toJson(int $flags = 0): string
    {
        try {
            return json_encode($this->toArray(), $flags | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw SerializationException::jsonEncodingFailed(static::class, $e);
        }
    }

    final public function jsonSerialize(): array
    {
        return $this->serializeToArray();
    }
}
