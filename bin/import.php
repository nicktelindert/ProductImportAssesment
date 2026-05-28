#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductImporter\Database;
use ProductImporter\Repositories\ProductRepository;
use ProductImporter\Services\Importer;
use GuzzleHttp\Client;

echo "------------------------------------------\n";
echo "🚀 Starten van Product Import...\n";
echo "------------------------------------------\n";

try {
    // Initialiseer de benodigdheden
    $database = new Database();
    $pdo = $database->getConnection();
    $repository = new ProductRepository($pdo);
    $client = new Client();
    $importer = new Importer($repository, $client);

    // Voer de import uit
    $count = $importer->import();

    if ($count > 0) {
        echo "✅ Succes: Er zijn $count producten succesvol geïmporteerd.\n";
    } else {
        echo "⚠️  Waarschuwing: Er zijn geen producten geïmporteerd. Controleer de API-status of logs.\n";
    }
} catch (Exception $e) {
    fwrite(STDERR, "❌ Fout tijdens import: " . $e->getMessage() . PHP_EOL);
    exit(1);
}

echo "------------------------------------------\n";
echo "Klaar!\n";