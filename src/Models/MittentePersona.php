<?php

namespace JustSolve\Raccomandate\Models;

class MittentePersona extends Mittente {

    public function __construct(
        protected string $nome, 
        protected string $cognome,
        string $dug, 
        string $indirizzo, 
        string $civico, 
        string $comune, 
        string $cap, 
        string $provincia, 
        string $nazione, 
        string $email,
        protected string $titolo = '' 
    ) {
        parent::__construct($dug, $indirizzo, $civico, $comune, $cap, $provincia, $nazione, $email);
    }

    // Getters
    public function getTitolo(): string { return $this->titolo; }
    public function getNome(): string { return $this->nome; }
    public function getCognome(): string { return $this->cognome; }

    // Setters
    public function setTitolo(string $titolo): void { $this->titolo = $titolo; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setCognome(string $cognome): void { $this->cognome = $cognome; }

    public function jsonSerialize(): mixed
    {
        return [
            'titolo' => $this->titolo,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
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
