<?php

namespace JustSolve\Raccomandate\Models;

class DestinatarioPersonaItalia extends DestinatarioPersona {

    public function __construct(
        string $nome, 
        string $cognome,
        protected string $dug,
        string $indirizzo,
        protected string $civico,
        string $comune,
        string $cap,
        protected string $provincia,
        string $nazione,
        string $titolo = '',
        protected string $co = '',
        protected string $dettaglio_immobile = '',
    ) {
        parent::__construct($nome, $cognome, $indirizzo, $comune, $cap, $nazione, $titolo);
    }

    // Getters for unique properties
    public function getDug(): string { return $this->dug; }
    public function getCivico(): string { return $this->civico; }
    public function getProvincia(): string { return $this->provincia; }
    public function getCo(): string { return $this->co; }
    public function getDettaglioImmobile(): string { return $this->dettaglio_immobile; }

    // Setters for unique properties
    public function setDug(string $dug): void { $this->dug = $dug; }
    public function setCivico(string $civico): void { $this->civico = $civico; }
    public function setProvincia(string $provincia): void { $this->provincia = $provincia; }
    public function setCo(string $co): void { $this->co = $co; }
    public function setDettaglioImmobile(string $dettaglio_immobile): void { $this->dettaglio_immobile = $dettaglio_immobile; }

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
            'dettaglio_immobile' => $this->dettaglio_immobile,
            'comune' => $this->comune,
            'cap' => $this->cap,
            'provincia' => $this->provincia,
            'nazione' => $this->nazione
        ];
    }
}
