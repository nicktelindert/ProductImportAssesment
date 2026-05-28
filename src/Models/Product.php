<?php

namespace ProductImporter\Models;

class Product
{
    public function __construct(
        public readonly int $external_id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $category,
        public readonly float $price,
        public readonly float $discount_percentage,
        public readonly ?string $brand,
        public readonly array $dimensions,
        public readonly array $images,
        public readonly ?string $thumbnail = null
    ) {}

    /**
     * Factory method om een Product te maken vanuit de API array.
     */
    public static function fromApi(array $data): self
    {
        return new self(
            external_id: $data['id'],
            title: $data['title'],
            description: $data['description'] ?? '',
            category: $data['category'] ?? '',
            price: (float)$data['price'],
            discount_percentage: (float)($data['discountPercentage'] ?? 0),
            brand: $data['brand'] ?? null,
            dimensions: $data['dimensions'] ?? [],
            images: $data['images'] ?? [],
            thumbnail: $data['thumbnail'] ?? null
        );
    }

    /**
     * Berekent de prijs na aftrek van de korting.
     */
    public function getDiscountedPrice(): float
    {
        $discount = $this->price * ($this->discount_percentage / 100);
        return round($this->price - $discount, 2);
    }

    /**
     * Zet het object om naar een array die 1-op-1 in de database past.
     */
    public function toDatabaseArray(): array
    {
        return [
            'external_id' => $this->external_id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'brand' => $this->brand,
            // Voor MariaDB JSON kolommen moeten we de data encoden
            'dimensions' => json_encode($this->dimensions),
            'images' => json_encode($this->images),
            'thumbnail' => $this->thumbnail,
        ];
    }
}