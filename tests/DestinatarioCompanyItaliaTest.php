<?php

use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Models\DestinatarioCompanyItalia;

class DestinatarioCompanyItaliaTest extends TestCase
{
    public function testCreateReturnsInstanceWithRequiredFields(): void
    {
        $destinatario = DestinatarioCompanyItalia::create([
            'ragioneSociale' => 'Acme Corp',
            'dug' => 'Via',
            'indirizzo' => 'Roma',
            'civico' => '1',
            'comune' => 'Milano',
            'cap' => '20100',
            'provincia' => 'MI',
            'nazione' => 'IT',
        ]);

        $this->assertInstanceOf(DestinatarioCompanyItalia::class, $destinatario);
    }

    public function testCreateThrowsExceptionWhenRequiredFieldMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required field 'dug' for JustSolve\\Raccomandate\\Models\\DestinatarioCompanyItalia");

        DestinatarioCompanyItalia::create([
            'ragioneSociale' => 'Acme Corp',
            'indirizzo' => 'Roma',
            'civico' => '1',
            'comune' => 'Milano',
            'cap' => '20100',
            'provincia' => 'MI',
            'nazione' => 'IT',
        ]);
    }
}
