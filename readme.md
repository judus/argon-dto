# Argon DTO

A base `AbstractDTO` class for creating immutable data transfer objects with support for:

- `fromArray()` and `fromJson()` hydration
- Recursive `toArray()` and `toJson()` serialization
- Strict constructor mapping via `AutoMappableInterface`
- Optional value equality via `equals()`

---

## Installation

Install via Composer:

```bash
composer require maduser/argon-dto
````

---

## Basic Usage

```php
use Maduser\Argon\DTO\AbstractDTO;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;

readonly class AddressDTO extends AbstractDTO implements AutoMappableInterface
{
    public function __construct(
        public string $street,
        public string $zipCode,
        public string $city
    ) {}

    public static function mapFromArray(array $data): array
    {
        return [
            $data['street'] ?? '',
            $data['zipCode'] ?? '',
            $data['city'] ?? '',
        ];
    }
}
```

```php
$data = [
    'street' => 'Main St. 12',
    'zipCode' => '12345',
    'city' => 'Exampletown'
];

// Hydration
$dto = AddressDTO::fromArray($data);

// JSON
$json = $dto->toJson();
$clone = AddressDTO::fromJson($json);

// Comparison
$dto->equals($clone); // true
```

---

## Nested DTOs

Nested DTOs are recursively serialized when using `toArray()` or `toJson()`.

```php
readonly class PersonDTO extends AbstractDTO implements AutoMappableInterface
{
    public function __construct(
        public string $name,
        public AddressDTO $address
    ) {}

    public static function mapFromArray(array $data): array
    {
        return [
            $data['name'] ?? '',
            AddressDTO::fromArray($data['address'] ?? []),
        ];
    }
}
```

```php
$person = PersonDTO::fromArray([
    'name' => 'Julien',
    'address' => [
        'street' => 'Rue de PHP',
        'zipCode' => '4000',
        'city' => 'Basel'
    ]
]);

$person->toArray();
// [
//   'name' => 'Julien',
//   'address' => [
//     'street' => 'Rue de PHP',
//     'zipCode' => '4000',
//     'city' => 'Basel'
//   ]
// ]

echo $person;
// JSON string with full nested structure
```

---

## Custom Serialization

If you need to customize the output format:

```php
protected function serializeToArray(): array
{
    return [
        'full_address' => "{$this->street}, {$this->zipCode} {$this->city}"
    ];
}
```

---

## Interface Overview

| Interface               | Purpose                                            |
| ----------------------- | -------------------------------------------------- |
| `AutoMappableInterface` | Defines `mapFromArray(array): array` for hydration |
| `Jsonable`              | Defines `toJson()`                                 |
| `FromJsonInterface`     | Defines `fromJson(string)`                         |
| `DTOInterface`          | Marker interface (optional, semantic)              |

---

## Error Handling

* `DTOConfigurationException` is thrown if `mapFromArray()` is missing.
* `SerializationException` wraps any `JsonException` during encoding.
* `JsonException` is thrown on invalid JSON input.

---

## Notes

* DTOs are `readonly` by design and require PHP 8.2+.
* Keys in `mapFromArray()` must match constructor arguments exactly (by order).
* Arrays of DTOs are serialized recursively as well.

---

## License
MIT — do what you want.
