[![PHP](https://img.shields.io/badge/php-8.2+-blue)](https://www.php.net/)
[![Build](https://github.com/judus/argon-dto/actions/workflows/php.yml/badge.svg)](https://github.com/judus/argon-dto/actions)
[![codecov](https://codecov.io/gh/judus/argon-dto/branch/master/graph/badge.svg)](https://codecov.io/gh/judus/argon-dto)
[![Psalm Level](https://shepherd.dev/github/judus/argon-dto/coverage.svg)](https://shepherd.dev/github/judus/argon-dto)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-brightgreen.svg)](https://www.php-fig.org/psr/psr-12/)
[![Latest Version](https://img.shields.io/packagist/v/maduser/argon-dto.svg)](https://packagist.org/packages/maduser/argon-dto)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

# Argon DTO

A lightweight PHP DTO implementation with support for array and JSON hydration, serialization, and optional auto-mapping using field constants.
Reflection at runtime is avoided by design.

## Requirements

* PHP 8.2+
* Composer

## Installation

```bash
composer require maduser/argon-dto
```

## Overview

This package provides:

* A base `AbstractDTO` class for immutable data transfer objects.
* Optional auto-mapping via `AutoMappableInterface` and `AutoMap`.
* Consistent serialization via `toArray()` and `toJson()`.
* Custom exceptions for misconfiguration or serialization errors.

## Usage

### Simple DTO

For a basic DTO, just extend `AbstractDTO`:

```php
use Maduser\Argon\DTO\AbstractDTO;

final class ProductDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
}

$dto = new ProductDTO(1, 'Gadget');
```

### Auto-Mapping DTO

If you want array-to-constructor mapping without manually unpacking arrays:

```php
use Maduser\Argon\DTO\AbstractDTO;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Traits\AutoMap;

final class UserDTO extends AbstractDTO implements AutoMappableInterface
{
    use AutoMap;

    public const MAPPABLE_FIELDS = ['id', 'name'];

    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
}
```

You can then hydrate from arrays or JSON:

```php
$dto = UserDTO::fromArray(['id' => 1, 'name' => 'Alice']);
$dto = UserDTO::fromJson('{"id":1,"name":"Alice"}');
```

### Accessing Data

```php
$array = $dto->toArray();
$json = $dto->toJson();
```

### Mapping Logic

If your DTO implements `AutoMappableInterface`, you must define a `mapFromArray()` method. You can implement this manually or use the provided `AutoMap` trait. The trait uses `MAPPABLE_FIELDS` to determine which keys to extract from the input array and in what order:

```php
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
```

* The order of keys in the input array is irrelevant.
* Extra keys in the input array are ignored.

## Error Handling

* `DTOConfigurationException` – DTO lacks required mapping behavior.
* `DTOMappingException` – Missing fields or invalid `MAPPABLE_FIELDS`.
* `DTOSerializationException` – JSON encoding/decoding failures.

## Testing

```bash
vendor/bin/phpunit
vendor/bin/psalm
vendor/bin/phpcs
```

# License

MIT
