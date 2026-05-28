<?php

namespace Tests;

use ProductImporter\Services\Importer;
use ProductImporter\Models\Product;
use ProductImporter\Repositories\ProductRepository;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ImporterTest extends TestCase
{
    public function test_it_imports_products_successfully(): void
    {
        // Arrange
        $mockRepository = $this->createMock(ProductRepository::class);
        $mockClient = $this->createMock(ClientInterface::class);

        $apiResponseData = [
            'products' => [
                [
                    'id' => 1,
                    'title' => 'Test Product 1',
                    'price' => 10.0,
                    'discountPercentage' => 5.0,
                ],
                [
                    'id' => 2,
                    'title' => 'Test Product 2',
                    'price' => 20.0,
                    'discountPercentage' => 10.0,
                ]
            ]
        ];

        $mockClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://dummyjson.com/products?limit=100')
            ->willReturn(new Response(200, [], json_encode($apiResponseData)));

        // We verwachten dat de save methode 2 keer wordt aangeroepen
        $mockRepository->expects($this->exactly(2))
            ->method('save')
            ->with($this->isInstanceOf(Product::class))
            ->willReturn(true);

        $importer = new Importer($mockRepository, $mockClient);

        // Act
        $result = $importer->import();

        // Assert
        $this->assertEquals(2, $result);
    }

    public function test_it_returns_zero_on_api_error_status(): void
    {
        // Arrange
        $mockRepository = $this->createMock(ProductRepository::class);
        $mockClient = $this->createMock(ClientInterface::class);

        $mockClient->method('request')
            ->willReturn(new Response(404));

        $importer = new Importer($mockRepository, $mockClient);

        // Act
        $result = $importer->import();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_it_returns_zero_on_invalid_json_payload(): void
    {
        // Arrange
        $mockRepository = $this->createMock(ProductRepository::class);
        $mockClient = $this->createMock(ClientInterface::class);

        $mockClient->method('request')
            ->willReturn(new Response(200, [], 'invalid-json'));

        $importer = new Importer($mockRepository, $mockClient);

        // Act
        $result = $importer->import();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_it_returns_zero_when_products_key_is_missing(): void
    {
        // Arrange
        $mockRepository = $this->createMock(ProductRepository::class);
        $mockClient = $this->createMock(ClientInterface::class);

        $mockClient->method('request')
            ->willReturn(new Response(200, [], json_encode(['foo' => 'bar'])));

        $importer = new Importer($mockRepository, $mockClient);

        // Act
        $result = $importer->import();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_it_returns_zero_on_client_exception(): void
    {
        // Arrange
        $mockRepository = $this->createMock(ProductRepository::class);
        $mockClient = $this->createMock(ClientInterface::class);

        $mockClient->method('request')
            ->willThrowException(new \Exception('Connection failed'));

        $importer = new Importer($mockRepository, $mockClient);

        // Act
        $result = $importer->import();

        // Assert
        $this->assertEquals(0, $result);
    }
}