<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ProductImporter\Database;
use ProductImporter\Models\Product;
use ProductImporter\Repositories\ProductRepository;
use PDO;

class DatabaseIntegrationTest extends TestCase
{
    private PDO $pdo;
    private ProductRepository $repository;
    private const TEST_EXTERNAL_ID = 99999;

    protected function setUp(): void
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $this->repository = new ProductRepository($this->pdo);

        // Verwijder eventuele restanten van een vorige testrun
        $this->pdo->prepare("DELETE FROM products WHERE external_id = ?")
            ->execute([self::TEST_EXTERNAL_ID]);
    }

    public function test_it_can_save_and_retrieve_a_product_via_repository(): void
    {
        // 1. Arrange: Maak een test product aan
        $apiData = [
            "id" => self::TEST_EXTERNAL_ID,
            "title" => "Repository Test Product",
            "description" => "A test description",
            "category" => "beauty",
            "price" => 15.99,
            "discountPercentage" => 5.00,
            "brand" => "TestBrand",
            "dimensions" => ["width" => 10, "height" => 10, "depth" => 10],
            "images" => ["test.jpg"],
            "thumbnail" => "thumb.jpg"
        ];

        $product = Product::fromApi($apiData);

        // 2. Act: Sla het product op via de repository
        $success = $this->repository->save($product);

        // 3. Assert: Controleer of de opslag gelukt is
        $this->assertTrue($success, "De repository save actie is mislukt.");

        // Haal het product weer op via de repository om de data-integriteit te controleren
        $savedProduct = $this->repository->findByExternalId(self::TEST_EXTERNAL_ID);

        $this->assertInstanceOf(Product::class, $savedProduct);
        $this->assertEquals("Repository Test Product", $savedProduct->title);
        $this->assertEquals(15.99, $savedProduct->price);
        $this->assertEquals("beauty", $savedProduct->category);
        $this->assertEquals("TestBrand", $savedProduct->brand);

        // 4. Act: Verwijder het product via de repository om de database schoon te houden
        $this->repository->deleteByExternalId(self::TEST_EXTERNAL_ID);

        // 5. Assert: Controleer of het product daadwerkelijk verwijderd is
        $deletedProduct = $this->repository->findByExternalId(self::TEST_EXTERNAL_ID);
        $this->assertNull($deletedProduct, "Het test product is niet correct verwijderd uit de database.");
    }
}