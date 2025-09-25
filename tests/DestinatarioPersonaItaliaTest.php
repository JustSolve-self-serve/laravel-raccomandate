<?php

use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Models\DestinatarioPersonaItalia;

class DestinatarioPersonaItaliaTest extends TestCase
{
    public function testCreateReturnsInstanceWithRequiredFields(): void
    {
        $destinatario = DestinatarioPersonaItalia::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'dug' => 'Via',
            'indirizzo' => 'Garibaldi',
            'civico' => '10',
            'comune' => 'Roma',
            'cap' => '00100',
            'provincia' => 'RM',
            'nazione' => 'IT',
        ]);

        $this->assertInstanceOf(DestinatarioPersonaItalia::class, $destinatario);
    }

    public function testCreateThrowsExceptionWhenRequiredFieldMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required field 'dug' for JustSolve\\Raccomandate\\Models\\DestinatarioPersonaItalia");

        DestinatarioPersonaItalia::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'indirizzo' => 'Garibaldi',
            'civico' => '10',
            'comune' => 'Roma',
            'cap' => '00100',
            'provincia' => 'RM',
            'nazione' => 'IT',
        ]);
    }
}
