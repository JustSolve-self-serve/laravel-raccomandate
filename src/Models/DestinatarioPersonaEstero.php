<?php

namespace JustSolve\Raccomandate\Models;

class DestinatarioPersonaEstero extends DestinatarioPersona {

    public function __construct(
        string $nome, 
        string $cognome,
        protected string $indirizzo, 
        protected string $comune, 
        protected string $cap,
        protected string $nazione, 
        protected string $titolo = '',
    ) {
        parent::__construct($nome, $cognome);
    }

    // Getters
    public function getIndirizzo(): string { return $this->indirizzo; }
    public function getComune(): string { return $this->comune; }
    public function getCap(): string { return $this->cap; }
    public function getNazione(): string { return $this->nazione; }
    public function getTitolo(): string { return $this->titolo; }

    // Setters
    public function setIndirizzo(string $indirizzo): void { $this->indirizzo = $indirizzo; }
    public function setComune(string $comune): void { $this->comune = $comune; }
    public function setCap(string $cap): void { $this->cap = $cap; }
    public function setNazione(string $nazione): void { $this->nazione = $nazione; }
    public function setTitolo(string $titolo): void { $this->titolo = $titolo; }

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
