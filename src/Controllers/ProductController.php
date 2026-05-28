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
        $products = $this->repository->findAll();

        // Laad de view en maak $products beschikbaar
        require __DIR__ . '/../../views/product_list.php';
    }
}