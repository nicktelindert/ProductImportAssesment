<?php

namespace ProductImporter\Controllers;

use ProductImporter\Database;
use ProductImporter\Repositories\ProductRepository;
use Exception;

class ProductController
{
    public function index(): void
    {
        try {
            // In een volwaardig framework zou de DB verbinding via Dependency Injection komen.
            $database = new Database();
            $repository = new ProductRepository($database->getConnection());
            $products = $repository->findAll();

            // Laad de view en maak $products beschikbaar
            require __DIR__ . '/../../views/product_list.php';
        } catch (Exception $e) {
            die("Er is een fout opgetreden bij het laden van de producten: " . $e->getMessage());
        }
    }
}