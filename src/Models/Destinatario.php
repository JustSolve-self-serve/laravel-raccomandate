<?php

namespace JustSolve\Raccomandate\Models;

interface Destinatario extends \JsonSerializable
{
    public static function create(array $properties): static;
}
