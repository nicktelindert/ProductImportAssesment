<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductImporter\Controllers\ProductController;
use ProductImporter\Database;
use ProductImporter\ErrorHandler;
use ProductImporter\Repositories\ProductRepository;

// Registreer de centrale foutafhandeling
set_exception_handler([ErrorHandler::class, 'handleException']);

// Initialiseer de dependencies handmatig
$database = new Database();
$repository = new ProductRepository($database->getConnection());

$controller = new ProductController($repository);
$controller->index();