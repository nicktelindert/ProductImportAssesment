<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductImporter\Controllers\ProductController;

$controller = new ProductController();
$controller->index();