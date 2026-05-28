<?php

namespace ProductImporter\Controllers;

use ProductImporter\Repositories\ProductRepository;

class ProductController
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function index(): void
    {
        $filters = [
            'category' => $_GET['category'] ?? null,
            'brand' => $_GET['brand'] ?? null,
        ];

        $sort = $_GET['sort'] ?? null;
        $order = $_GET['order'] ?? 'ASC';

        $products = $this->repository->findAll($filters, $sort, $order);
        $categories = $this->repository->getUniqueCategories();
        $brands = $this->repository->getUniqueBrands();

        // Laad de view en maak $products beschikbaar
        http_response_code(200);
        require __DIR__ . '/../../views/product_list.php';
    }

    public function show(int $id): void
    {
        $product = $this->repository->findByExternalId($id);

        if (!$product) {
            throw new \Exception("Product met ID $id niet gevonden.", 404);
        }

        http_response_code(200);
        require __DIR__ . '/../../views/product_detail.php';
    }
}