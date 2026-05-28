<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ProductImporter\Models\Product;

class ProductMappingTest extends TestCase
{
    private array $apiData;

    protected function setUp(): void
    {
        // We gebruiken het eerste product uit de API als test data
        $this->apiData = [
            "id" => 1,
            "title" => "Essence Mascara Lash Princess",
            "description" => "The Essence Mascara Lash Princess is a popular mascara...",
            "category" => "beauty",
            "price" => 9.99,
            "discountPercentage" => 10.48,
            "brand" => "Essence",
            "dimensions" => [
                "width" => 15.14,
                "height" => 13.08,
                "depth" => 22.99
            ],
            "images" => [
                "https://cdn.dummyjson.com/product-images/beauty/essence-mascara-lash-princess/1.webp"
            ]
        ];
    }

    public function test_it_maps_api_data_to_database_columns(): void
    {
        $product = Product::fromApi($this->apiData);
        $dbArray = $product->toDatabaseArray();

        // Controleer of de ID gemapt is naar external_id
        $this->assertEquals(1, $dbArray['external_id']);
        
        // Controleer mapping van camelCase naar snake_case
        $this->assertEquals(10.48, $dbArray['discount_percentage']);
        
        // Controleer of objecten/arrays correct naar JSON strings worden omgezet voor de DB
        $this->assertIsString($dbArray['dimensions']);
        $this->assertStringContainsString('15.14', $dbArray['dimensions']);
        
        // Controleer de logica voor de kortingsprijs (Should have: prijs - korting%)
        // 9.99 - (10.48% van 9.99) = ~8.94
        $expectedDiscountPrice = round(9.99 * (1 - (10.48 / 100)), 2);
        $this->assertEquals($expectedDiscountPrice, $product->getDiscountedPrice());
        
        $this->assertEquals('Essence', $dbArray['brand']);
    }
}