<?php

namespace JustSolve\Raccomandate\Models;

abstract class DestinatarioPersona implements Destinatario {
    public function __construct(
        protected string $nome,
        protected string $cognome,
        protected string $indirizzo,
        protected string $comune,
        protected string $cap,
        protected string $nazione,
        protected string $titolo = ''
    ) {}

    // Getters
    public function getNome(): string { return $this->nome; }
    public function getCognome(): string { return $this->cognome; }
    public function getIndirizzo(): string { return $this->indirizzo; }
    public function getComune(): string { return $this->comune; }
    public function getCap(): string { return $this->cap; }
    public function getNazione(): string { return $this->nazione; }
    public function getTitolo(): string { return $this->titolo; }

    // Setters
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setCognome(string $cognome): void { $this->cognome = $cognome; }
    public function setIndirizzo(string $indirizzo): void { $this->indirizzo = $indirizzo; }
    public function setComune(string $comune): void { $this->comune = $comune; }
    public function setCap(string $cap): void { $this->cap = $cap; }
    public function setNazione(string $nazione): void { $this->nazione = $nazione; }
    public function setTitolo(string $titolo): void { $this->titolo = $titolo; }
}