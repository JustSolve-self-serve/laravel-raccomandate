<?php

namespace JustSolve\Raccomandate\Models;

class MittenteCompany extends Mittente {
    protected string $ragioneSociale;

    public function __construct(
        string $ragioneSociale,
        string $dug, 
        string $indirizzo, 
        string $civico, 
        string $comune, 
        string $cap, 
        string $provincia, 
        string $nazione, 
        string $email
    ) {
        parent::__construct($dug, $indirizzo, $civico, $comune, $cap, $provincia, $nazione, $email);
        $this->ragioneSociale = $ragioneSociale;
    }

    // Getters
    public function getRagioneSociale(): string { return $this->ragioneSociale; }

    // Setters
    public function setRagioneSociale(string $ragioneSociale): void { $this->ragioneSociale = $ragioneSociale; }

    public function jsonSerialize(): mixed
    {
        return [
            'ragione_sociale' => $this->ragioneSociale,
            'dug' => $this->dug,
            'indirizzo' => $this->indirizzo,
            'civico' => $this->civico,
            'comune' => $this->comune,
            'cap' => $this->cap,
            'provincia' => $this->provincia,
            'nazione' => $this->nazione,
            'email' => $this->email
        ];
    }
}
