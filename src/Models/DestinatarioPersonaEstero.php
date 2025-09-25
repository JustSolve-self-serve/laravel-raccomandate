<?php

namespace JustSolve\Raccomandate\Models;

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
}
