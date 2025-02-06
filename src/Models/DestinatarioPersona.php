<?php

namespace JustSolve\Raccomandate\Models;

abstract class DestinatarioPersona implements Destinatario{
    public function __construct(
       protected string $nome,
       protected string $cognome
    ) {}

    // Getters
    public function getNome(): string { return $this->nome; }
    public function getCognome(): string { return $this->cognome; }

    // Setters
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setCognome(string $cognome): void { $this->cognome = $cognome; }
}