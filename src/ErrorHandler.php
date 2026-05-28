<?php

namespace ProductImporter;

use Throwable;

class ErrorHandler
{
    public static bool $enableLogging = true;

    public static function handleException(Throwable $exception): void
    {
        self::render($exception);
        exit();
    }

    public static function render(Throwable $exception): void
    {
        if (self::$enableLogging) {
            // Log voor de developer
            error_log(
                "Application Error: " . $exception->getMessage() . 
                " in " . $exception->getFile() . 
                " on line " . $exception->getLine()
            );
        }

        if (!headers_sent()) {
            http_response_code(500);
        }

        $errorMessage = $exception->getMessage();
        require __DIR__ . '/../views/error.php';
    }
}