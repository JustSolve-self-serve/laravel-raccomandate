<?php

namespace JustSolve\Raccomandate\Models;

use InvalidArgumentException;

class DestinatarioPersonaEstero extends DestinatarioPersona {

    public function __construct(
        string $nome, 
        string $cognome,
        string $indirizzo,
        string $comune,
        string $cap,
        string $nazione,
        string $titolo = ''
    ) {
        parent::__construct($nome, $cognome, $indirizzo, $comune, $cap, $nazione, $titolo);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'titolo' => $this->titolo,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            'indirizzo' => $this->indirizzo,
            'comune' => $this->comune,
            'cap' => $this->cap,
            'nazione' => $this->nazione
        ];
    }

    public static function create(array $properties): static
    {
        $requiredFields = ['nome', 'cognome', 'indirizzo', 'comune', 'cap', 'nazione'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $properties)) {
                throw new InvalidArgumentException(sprintf("Missing required field '%s' for %s", $field, static::class));
            }
        }

        return new static(
            $properties['nome'],
            $properties['cognome'],
            $properties['indirizzo'],
            $properties['comune'],
            $properties['cap'],
            $properties['nazione'],
            $properties['titolo'] ?? ''
        );
    }
}
