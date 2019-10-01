<?php

declare(strict_types=1);
include_once __DIR__ . '/stubs/Validator.php';
class ZaehlerUeberlaufValidationTest extends TestCaseSymconValidation
{
    public function testValidateZaehlerUeberlauf(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }
    public function testValidateZaehlerUebrlaufModule(): void
    {
        $this->validateModule(__DIR__ . '/../ZaehlerUeberlauf');
    }
}