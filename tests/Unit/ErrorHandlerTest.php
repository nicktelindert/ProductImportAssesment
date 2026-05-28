<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ProductImporter\ErrorHandler;
use Exception;

class ErrorHandlerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function test_it_renders_error_view_with_exception_message(): void
    {
        // Schakel logging uit voor deze test om "unexpected output" te voorkomen(risky test)
        ErrorHandler::$enableLogging = false;

        ob_start();
        ErrorHandler::render(new Exception('Specifieke Test Foutmelding'));
        $output = ob_get_clean();

        $this->assertStringContainsString('Specifieke Test Foutmelding', $output);
        $this->assertStringContainsString('Oeps! Er is iets misgegaan.', $output);
        $this->assertEquals(500, http_response_code());
    }
}