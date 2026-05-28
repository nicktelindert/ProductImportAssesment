<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ProductImporter\Controllers\ProductController;
use ProductImporter\Repositories\ProductRepository;
use ProductImporter\Models\Product;

class ProductControllerTest extends TestCase
{
    public function test_index_fetches_products_and_loads_view(): void
    {
        // Arrange
        $repositoryMock = $this->createMock(ProductRepository::class);
        
        $products = [
            new Product(
                external_id: 123,
                title: 'Controller Test Product',
                description: 'Een product om de controller te testen',
                category: 'test-category',
                price: 99.99,
                discount_percentage: 10.0,
                brand: 'TestBrand',
                dimensions: [],
                images: [],
                thumbnail: 'test.jpg'
            )
        ];

        $repositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($products);

        $controller = new ProductController($repositoryMock);

        // Act
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        // Assert
        $this->assertStringContainsString('Controller Test Product', $output);
        $this->assertStringContainsString('TestBrand', $output);
        $this->assertStringContainsString('Totaal aantal producten: 1', $output);
    }
}