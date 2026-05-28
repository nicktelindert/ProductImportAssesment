<?php

namespace ProductImporter\Services;

use GuzzleHttp\ClientInterface;
use ProductImporter\Models\Product;
use ProductImporter\Repositories\ProductRepository;
use Exception;

class Importer
{
    private const API_URL = 'https://dummyjson.com/products?limit=100';

    public function __construct(
        private readonly ProductRepository $repository,
        private readonly ClientInterface $client
    ) {}

    /**
     * Haalt producten op van de API en slaat ze op in de database via de repository.
     * Retourneert het aantal succesvol geïmporteerde producten.
     */
    public function import(): int
    {
        try {
            $response = $this->client->request('GET', self::API_URL);

            if ($response->getStatusCode() !== 200) {
                return 0;
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['products']) || !is_array($data['products'])) {
                return 0;
            }

            $importedCount = 0;
            foreach ($data['products'] as $productData) {
                $product = Product::fromApi($productData);
                if ($this->repository->save($product)) {
                    $importedCount++;
                }
            }

            return $importedCount;
        } catch (Exception $e) {
            // In een productie-omgeving zou je dit loggen naar een error logger.
            return 0;
        }
    }
}