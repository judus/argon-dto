<?php

declare(strict_types=1);

namespace Tests\Integration;

use InvalidArgumentException;
use JsonException;
use Maduser\Argon\DTO\AbstractDTO;
use Maduser\Argon\DTO\Contracts\AutoMappableInterface;
use Maduser\Argon\DTO\Exception\DTOConfigurationException;
use Maduser\Argon\DTO\Exception\DTOMappingException;
use Maduser\Argon\DTO\Exception\DTOSerializationException;
use PHPUnit\Framework\TestCase;
use Tests\Mocks\AutoMapMissingConstantDTO;
use Tests\Mocks\AutoMapMissingFieldDTO;
use Tests\Mocks\JsonEncodingFailureDTO;
use Tests\Mocks\NonAutoMappedDTO;
use Tests\Mocks\UserDTO;

final class DTOTest extends TestCase
{
    public function testFromArrayWithValidData(): void
    {
        $dto = UserDTO::fromArray(['id' => 1, 'name' => 'Suze']);
        $this->assertSame(1, $dto->id);
        $this->assertSame('Suze', $dto->name);
    }

    /**
     * @throws JsonException
     */
    public function testFromJsonWithValidJson(): void
    {
        $json = '{"id":2,"name":"Freakin\' Suze"}';
        $dto = UserDTO::fromJson($json);

        $this->assertSame(2, $dto->id);
        $this->assertSame("Freakin' Suze", $dto->name);
    }

    public function testToArrayReturnsCorrectData(): void
    {
        $dto = new UserDTO(3, 'Sugar');
        $this->assertSame(['id' => 3, 'name' => 'Sugar'], $dto->toArray());
    }

    /**
     * @throws JsonException
     */
    public function testToJsonReturnsValidJson(): void
    {
        $dto = new UserDTO(4, 'Honey');
        $json = $dto->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('"name":"Honey"', $json);
    }

    /**
     * @throws JsonException
     */
    public function testFromJsonThrowsOnInvalidJson(): void
    {
        $this->expectException(DTOSerializationException::class);
        UserDTO::fromJson('not-json');
    }

    public function testFromArrayThrowsIfMissingAutoMap(): void
    {
        $this->expectException(DTOConfigurationException::class);
        NonAutoMappedDTO::fromArray(['id' => 1]);
    }

    public function testAutoMapMissingConstant(): void
    {
        $this->expectException(DTOMappingException::class);
        $this->expectExceptionMessage(
            'Class "Tests\Mocks\AutoMapMissingConstantDTO" is missing MAPPABLE_FIELDS.'
        );

        AutoMapMissingConstantDTO::mapFromArray(['id' => 1, 'name' => 'Miss']);
    }

    public function testAutoMapMissingField(): void
    {
        $this->expectException(DTOMappingException::class);
        $this->expectExceptionMessage(
            'Field "name" is missing from input when mapping Tests\Mocks\AutoMapMissingFieldDTO.'
        );

        AutoMapMissingFieldDTO::mapFromArray(['id' => 1]); // Missing 'name'
    }

    public function testMapDelegatesToFromArray(): void
    {
        $dto = UserDTO::map(['id' => 5, 'name' => 'Delegate']);
        $this->assertInstanceOf(UserDTO::class, $dto);
        $this->assertSame(5, $dto->id);
        $this->assertSame('Delegate', $dto->name);
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerializeUsesSerializeToArray(): void
    {
        $dto = new UserDTO(6, 'Suze JSON');
        $json = json_encode($dto, JSON_THROW_ON_ERROR);

        $this->assertJson($json);
        $this->assertStringContainsString('"name":"Suze JSON"', $json);
    }

    /**
     * @throws DTOSerializationException
     */
    public function testToJsonThrowsDTOSerializationException(): void
    {
        $this->expectException(DTOSerializationException::class);
        $dto = new JsonEncodingFailureDTO();
        $dto->toJson();
    }
}
