<?php

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;

class RaccomandateServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('raccomandate.base_uri', env('RACCOMANDATE_BASE_URI', 'baba'));
        Config::set('raccomandate.api_key', env('RACCOMANDATE_API_KEY', 'bubu'));
    }

    public function testListRaccomandate()
    {
        $this->assertNull(Raccomandate::listRaccomandate());
    }
}
