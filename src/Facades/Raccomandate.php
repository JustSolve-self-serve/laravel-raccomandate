<?php

namespace YourVendor\Raccomandate\Facades;

use Illuminate\Support\Facades\Facade;
use YourVendor\Raccomandate\RaccomandateService;

class Raccomandate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RaccomandateService::class;
    }
}
