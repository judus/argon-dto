[![PHP](https://img.shields.io/badge/php-8.2+-blue)](https://www.php.net/)
[![Build](https://github.com/judus/argon-dto/actions/workflows/php.yml/badge.svg)](https://github.com/judus/argon-dto/actions)
[![codecov](https://codecov.io/gh/judus/argon-dto/branch/master/graph/badge.svg)](https://codecov.io/gh/judus/argon-dto)
[![Psalm Level](https://shepherd.dev/github/judus/argon-dto/coverage.svg)](https://shepherd.dev/github/judus/argon-dto)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-brightgreen.svg)](https://www.php-fig.org/psr/psr-12/)
[![Latest Version](https://img.shields.io/packagist/v/maduser/argon-dto.svg)](https://packagist.org/packages/maduser/argon-dto)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

# maduser/argon-dto

A lightweight PHP DTO implementation with support for array and JSON hydration, serialization, and auto-mapping using field constants.

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer require maduser/argon-dto
````

## Overview

This package provides:

* A base `AbstractDTO` class for immutable data transfer objects
* Interfaces for consistent serialization and mapping
* A trait for automatic mapping from arrays using field constants
* Custom exceptions for misconfiguration or serialization errors

## Interfaces

* `DTOInterface` – Combines `Arrayable` and `Jsonable`
* `Arrayable` – Defines `toArray(): array`
* `Jsonable` – Defines `toJson(int $flags = 0): string`
* `FromArrayInterface` – Defines `fromArray(array $data): static`
* `FromJsonInterface` – Defines `fromJson(string $json): static`
* `AutoMappableInterface` – Defines `mapFromArray(array $data): array`

## Usage

### Define a DTO

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

### Create from array

```php
$dto = UserDTO::fromArray(['id' => 1, 'name' => 'Alice']);
```

### Create from JSON

```php
$dto = UserDTO::fromJson('{"id":1,"name":"Alice"}');
```

### Access data

```php
$array = $dto->toArray();
$json = $dto->toJson();
```

### Mapping

```php
$dto = UserDTO::map(['id' => 1, 'name' => 'Alice']);
```

## Design: `fromArray()` vs `map()`

* `fromArray(array $data): static` is the primary hydration method. It performs validation and field mapping as needed and is the intended entry point for consumers creating DTOs from raw input.

* `map(array $row): static` is a convenience wrapper that defaults to calling `fromArray()`. It exists to support integration scenarios where a generic mapping method is expected, such as automatic hydration from database rows.

For example, the `map()` method fulfills the `RowMapper` interface required by the `fetchOneTo()` and `fetchAllTo()` methods in the [`judus/argon-database`](https://packagist.org/packages/judus/argon-database) package.

They are functionally identical unless overridden. Use `fromArray()` in most cases; use `map()` where structural compatibility is required.

## Error Handling

* `DTOConfigurationException` – Thrown if a DTO lacks required mapping behavior
* `DTOMappingException` – Thrown for missing fields or constants in mapping
* `DTOSerializationException` – Thrown on JSON encoding or decoding failure

## Testing

Run PHPUnit:

```bash
vendor/bin/phpunit
```

Run Psalm:

```bash
vendor/bin/psalm
```

Run PHP\_CodeSniffer:

```bash
vendor/bin/phpcs
```
