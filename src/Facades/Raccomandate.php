<?php

namespace JustSolve\Raccomandate\Facades;

use Illuminate\Support\Facades\Facade;
use JustSolve\Raccomandate\RaccomandateService;

class Raccomandate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RaccomandateService::class;
    }
}
