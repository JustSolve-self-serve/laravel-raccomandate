<?php

namespace JustSolve\Raccomandate\Models;

abstract class DestinatarioCompany implements Destinatario{
    public function __construct(
       protected string $ragioneSociale
    ) {}

    // Getters
    public function getRagioneSociale(): string { return $this->ragioneSociale; }

    // Setters
    public function setRagioneSociale(string $ragioneSociale): void { $this->ragioneSociale = $ragioneSociale; }
}