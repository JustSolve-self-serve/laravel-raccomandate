<?php

namespace JustSolve\Raccomandate\Models;

use JsonSerializable;

abstract class Mittente implements JsonSerializable{
    public function __construct(
       protected string $dug,
       protected string $indirizzo,
       protected string $civico,
       protected string $comune,
       protected string $cap,
       protected string $provincia,
       protected string $nazione,
       protected string $email
    ) {}

    // Getters
    public function getDug(): string { return $this->dug; }
    public function getIndirizzo(): string { return $this->indirizzo; }
    public function getCivico(): string { return $this->civico; }
    public function getComune(): string { return $this->comune; }
    public function getCap(): string { return $this->cap; }
    public function getProvincia(): string { return $this->provincia; }
    public function getNazione(): string { return $this->nazione; }
    public function getEmail(): string { return $this->email; }

    // Setters
    public function setDug(string $dug): void { $this->dug = $dug; }
    public function setIndirizzo(string $indirizzo): void { $this->indirizzo = $indirizzo; }
    public function setCivico(string $civico): void { $this->civico = $civico; }
    public function setComune(string $comune): void { $this->comune = $comune; }
    public function setCap(string $cap): void { $this->cap = $cap; }
    public function setProvincia(string $provincia): void { $this->provincia = $provincia; }
    public function setNazione(string $nazione): void { $this->nazione = $nazione; }
    public function setEmail(string $email): void { $this->email = $email; }
}
