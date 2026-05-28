<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductImporter\Controllers\ProductController;
use ProductImporter\Database;
use ProductImporter\ErrorHandler;
use ProductImporter\Repositories\ProductRepository;
use ProductImporter\Router;

// Registreer de centrale foutafhandeling
set_exception_handler([ErrorHandler::class, 'handleException']);

// Initialiseer de dependencies handmatig
$database = new Database();
$repository = new ProductRepository($database->getConnection());

// Start de Router met de benodigde dependencies voor de controllers
$router = new Router([$repository]);
$router->dispatch();