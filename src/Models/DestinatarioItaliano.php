<?php

namespace JustSolve\Raccomandate\Models;

class DestinatarioItaliano extends Destinatario {

    public function __construct(
        string $nome, 
        string $cognome,
        protected string $dug, 
        protected string $indirizzo, 
        protected string $civico, 
        protected string $comune, 
        protected string $cap, 
        protected string $provincia, 
        protected string $nazione, 
        protected string $titolo = '', 
        protected string $co = ''
    ) {
        parent::__construct($nome, $cognome);
    }

    // Getters
    public function getDug(): string { return $this->dug; }
    public function getIndirizzo(): string { return $this->indirizzo; }
    public function getCivico(): string { return $this->civico; }
    public function getComune(): string { return $this->comune; }
    public function getCap(): string { return $this->cap; }
    public function getProvincia(): string { return $this->provincia; }
    public function getNazione(): string { return $this->nazione; }
    public function getTitolo(): string { return $this->titolo; }
    public function getCo(): string { return $this->co; }

    // Setters
    public function setDug(string $dug): void { $this->dug = $dug; }
    public function setIndirizzo(string $indirizzo): void { $this->indirizzo = $indirizzo; }
    public function setCivico(string $civico): void { $this->civico = $civico; }
    public function setComune(string $comune): void { $this->comune = $comune; }
    public function setCap(string $cap): void { $this->cap = $cap; }
    public function setProvincia(string $provincia): void { $this->provincia = $provincia; }
    public function setNazione(string $nazione): void { $this->nazione = $nazione; }
    public function setTitolo(string $titolo): void { $this->titolo = $titolo; }
    public function setCo(string $co): void { $this->co = $co; }

    public function jsonSerialize(): mixed
    {
        return [
            'titolo' => $this->titolo,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            'co' => $this->co,
            'dug' => $this->dug,
            'indirizzo' => $this->indirizzo,
            'civico' => $this->civico,
            'comune' => $this->comune,
            'cap' => $this->cap,
            'provincia' => $this->provincia,
            'nazione' => $this->nazione
        ];
    }
}
