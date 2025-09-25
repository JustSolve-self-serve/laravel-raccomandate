<?php

use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Models\DestinatarioPersonaEstero;

class DestinatarioPersonaEsteroTest extends TestCase
{
    public function testCreateReturnsInstanceWithRequiredFields(): void
    {
        $destinatario = DestinatarioPersonaEstero::create([
            'nome' => 'Anna',
            'cognome' => 'Bianchi',
            'indirizzo' => 'Main Street',
            'comune' => 'Paris',
            'cap' => '75000',
            'nazione' => 'FR',
        ]);

        $this->assertInstanceOf(DestinatarioPersonaEstero::class, $destinatario);
    }

    public function testCreateThrowsExceptionWhenRequiredFieldMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required field 'cap' for JustSolve\\Raccomandate\\Models\\DestinatarioPersonaEstero");

        DestinatarioPersonaEstero::create([
            'nome' => 'Anna',
            'cognome' => 'Bianchi',
            'indirizzo' => 'Main Street',
            'comune' => 'Paris',
            'nazione' => 'FR',
        ]);
    }
}
