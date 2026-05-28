<?php

namespace ProductImporter\Repositories;

use ProductImporter\Models\Product;
use PDO;

class ProductRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /**
     * Slaat een product op in de database. Gebruikt een upsert (INSERT ... ON DUPLICATE KEY UPDATE).
     */
    public function save(Product $product): bool
    {
        $data = $product->toDatabaseArray();
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);
        $updateParts = array_map(fn($col) => "$col = VALUES($col)", $columns);

        $sql = sprintf(
            "INSERT INTO products (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
            implode(', ', $columns),
            implode(', ', $placeholders),
            implode(', ', $updateParts)
        );

        return $this->pdo->prepare($sql)->execute($data);
    }

    /**
     * Haalt alle producten op uit de database.
     * @return Product[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => $this->mapToProduct($row), $rows);
    }

    /**
     * Zoekt een product op basis van het externe ID.
     */
    public function findByExternalId(int $externalId): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE external_id = ?");
        $stmt->execute([$externalId]);
        $row = $stmt->fetch();

        return $row ? $this->mapToProduct($row) : null;
    }

    /**
     * Verwijdert een product op basis van het externe ID.
     */
    public function deleteByExternalId(int $externalId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE external_id = ?");
        return $stmt->execute([$externalId]);
    }

    /**
     * Mapt een database rij naar een Product object.
     */
    private function mapToProduct(array $row): Product
    {
        return new Product(
            external_id: (int)$row['external_id'],
            title: $row['title'],
            description: $row['description'] ?? '',
            category: $row['category'] ?? '',
            price: (float)$row['price'],
            discount_percentage: (float)$row['discount_percentage'],
            brand: $row['brand'],
            dimensions: json_decode($row['dimensions'], true) ?? [],
            images: json_decode($row['images'], true) ?? [],
            thumbnail: $row['thumbnail']
        );
    }
}