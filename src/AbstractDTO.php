<?php

declare(strict_types=1);

namespace Maduser\Argon\DTO;

use JsonException;
use JsonSerializable;
use Maduser\Argon\DTO\Contracts\Arrayable;
use Maduser\Argon\DTO\Contracts\DTOInterface;
use Maduser\Argon\DTO\Contracts\FromJsonInterface;
use Maduser\Argon\DTO\Contracts\Jsonable;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Exception\DTOConfigurationException;
use Maduser\Argon\DTO\Exception\DTOSerializationException;

abstract readonly class AbstractDTO implements DTOInterface, FromJsonInterface, jsonSerializable
{
    /** @param array<array-key, mixed> $data */
    /** @psalm-suppress UnusedVariable we're unpacking $params in a constructor, get ready for a psalm-fest */
    final public static function fromArray(array $data): static
    {
        if (!is_subclass_of(static::class, AutoMappableInterface::class)) {
            throw DTOConfigurationException::missingAutoMapper(static::class);
        }

        /** @var class-string<AutoMappableInterface> $class */
        $class = static::class;

        $params = $class::mapFromArray($data);

        /** @psalm-suppress UnsafeInstantiation yeah, that's the whole point */
        /** @psalm-suppress TooManyArguments really now? what about not enough? It'll blow, and that's what we want */
        return new static(...$params);
    }

    /**
     * Default method for consumers like RowMapper — not enforced here.
     *
     * @param array<string, scalar|null> $row
     *
     * @api
     */
    public static function map(array $row): static
    {
        return static::fromArray($row);
    }

    final public static function fromJson(string $json): static
    {
        try {
            /** @var array<string, scalar|null> $data */
            $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw DTOSerializationException::jsonDecodingFailed(static::class, $e);
        }

        return static::fromArray($data);
    }

    /** @return array<string, mixed> */
    final public function toArray(): array
    {
        return $this->serializeToArray();
    }

    /** @return array<string, mixed> */
    protected function serializeToArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @throws DTOSerializationException
     * @psalm-suppress FalsableReturnStatement we're catching JsonException and rethrowing it as a custom exception
     * @psalm-suppress InvalidFalsableReturnType we're catching JsonException and rethrowing it as a custom exception
     */
    final public function toJson(int $flags = 0): string
    {
        try {
            return json_encode($this->toArray(), $flags | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw DTOSerializationException::jsonEncodingFailed(static::class, $e);
        }
    }

    final public function jsonSerialize(): array
    {
        return $this->serializeToArray();
    }
}
