<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use ProductImporter\Router;
use ProductImporter\Repositories\ProductRepository;
use ProductImporter\Models\Product;
use ProductImporter\ErrorHandler;

class RouterIntegrationTest extends TestCase
{
    private array $originalGet;
    private ?string $originalUri;

    protected function setUp(): void
    {
        parent::setUp();
        // Sla de originele $_GET op en reset deze voor elke test
        $this->originalGet = $_GET;
        $_GET = [];

        // Sla de originele URI op
        $this->originalUri = $_SERVER['REQUEST_URI'] ?? null;

        // Schakel logging van de ErrorHandler uit voor tests om "unexpected output" te voorkomen
        ErrorHandler::$enableLogging = false;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Herstel de originele $_GET na elke test
        $_GET = $this->originalGet;

        // Herstel de originele URI
        if ($this->originalUri !== null) {
            $_SERVER['REQUEST_URI'] = $this->originalUri;
        }

        // Herstel de logging van de ErrorHandler
        ErrorHandler::$enableLogging = true;
    }

    /**
     * @runInSeparateProcess
     */
    public function test_root_url_dispatches_to_product_controller_index(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = '/';

        $mockRepository = $this->createMock(ProductRepository::class);
        $mockRepository->method('findAll')->willReturn([
            new Product(1, 'Test Product Router', 'Desc', 'Cat', 10.0, 0.0, 'Brand', [], [], null)
        ]);
        $mockRepository->method('getUniqueCategories')->willReturn(['Cat']);
        $mockRepository->method('getUniqueBrands')->willReturn(['Brand']);

        $router = new Router([$mockRepository]);

        // Act
        ob_start();
        $router->dispatch();
        $output = ob_get_clean();

        // Assert
        $this->assertStringContainsString('<h1>Productoverzicht</h1>', $output);
        $this->assertStringContainsString('Test Product Router', $output);
        $this->assertStringContainsString('Totaal aantal producten: 1', $output);
        $this->assertEquals(200, http_response_code()); // Verwacht een succesvolle HTTP status
    }

    /**
     * @runInSeparateProcess
     */
    public function test_detail_page_route_dispatches_to_product_controller_show(): void
    {
        // Arrange
        $id = 1;
        $slug = 'test-product-details';
        $_SERVER['REQUEST_URI'] = "/product/show/{$id}/{$slug}";

        $mockProduct = new Product($id, 'Test Product Details', 'Desc', 'Cat', 10.0, 0.0, 'Brand', ['width' => 10], ['img1.jpg'], 'thumb.jpg');

        $mockRepository = $this->createMock(ProductRepository::class);
        $mockRepository->expects($this->once())
            ->method('findByExternalId')
            ->with($id)
            ->willReturn($mockProduct);

        $router = new Router([$mockRepository]);

        // Act
        ob_start();
        $router->dispatch();
        $output = ob_get_clean();

        // Assert
        $this->assertStringContainsString('Test Product Details - Details', $output);
        $this->assertStringContainsString('Specificaties', $output);
        $this->assertStringContainsString('Breedte:</dt><dd>10 cm</dd>', $output);
        $this->assertEquals(200, http_response_code());
    }
}